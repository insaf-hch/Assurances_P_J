<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AiExtractionService
{
    public const SCHEMA_KEYS = [
        'numero_dossier',
        'numero_jugement',
        'date_jugement',
        'nom_assurance',
        'adresse_assurance',
        'nom_victime',
        'montant_initial',        // ← somme finale (rasemal + taawidat si cas mixte)
        'montant_rasemal_ijmali', // ← nouveau
        'montant_taawidat_youmiya', // ← nouveau
        'type_cas',
        'type_malaf',
    ];

    public function extractStructured(string $ocrText): array
    {
        $prompt = $this->buildPrompt($ocrText);

        $data = $this->tryGemini($prompt);
        if ($data === null) {
            $data = $this->tryGroq($prompt);
        }

        if ($data === null) {
            throw new RuntimeException(
                'Aucune extraction IA possible : définissez GEMINI_API_KEY ou GROQ_API_KEY dans .env.'
            );
        }

        $data = $this->validateAndNormalize($data);

// 🔥 CALCUL BACKEND (FIABLE)
$rasemal = (float)($data['montant_rasemal_ijmali'] ?? 0);
$taawidat = (float)($data['montant_taawidat_youmiya'] ?? 0);

if ($rasemal > 0 || $taawidat > 0) {
    $data['montant_initial'] = $rasemal + $taawidat;
}

return $data;
    }

    protected function buildPrompt(string $ocrText): string
    {
        Log::debug('OCR envoyé longueur', [
            'longueur'      => strlen($ocrText),
            'extrait_500'   => mb_substr($ocrText, 0, 500, 'UTF-8'),
            'contient_درهم' => str_contains($ocrText, 'درهم') ? 'OUI' : 'NON',
            'contient_قدره' => str_contains($ocrText, 'قدره') ? 'OUI' : 'NON',
        ]);
            Log::debug('OCR COMPLET', ['text' => $ocrText]);
        return 'Tu es un expert en dossiers judiciaires marocains (accidents du travail, assurances). '
            .'À partir du texte OCR ci-dessous, extrais UNIQUEMENT un objet JSON valide avec exactement ces clés : '
            .'numero_dossier, numero_jugement, date_jugement, nom_assurance, adresse_assurance, nom_victime (chaînes, "" si inconnu), '
            .'montant_initial (nombre décimal ou null), '
            .'montant_rasemal_ijmali (nombre décimal ou null), '
            .'montant_taawidat_youmiya (nombre décimal ou null), '
            .'type_cas (string ou null), '
            .'type_malaf (string : description courte du dossier, ex: "إيراد عمري" ou "" si inconnu). '
            ."\n\n"

            // ======================================================
            // RÈGLES type_cas — AJOUTE TES NOUVELLES RÈGLES ICI
            // ======================================================
            .'=== RÈGLES POUR type_cas ==='
            ."\n".'- Si tu trouves "إيرادا عمريا سنويا محولا لراسمال" ET "تعويضات يومية" → type_cas = "masdar_total_taawidat"'
            ."\n".'- Si tu trouves "إيرادا عمريا سنويا محولا لراسمال" SANS "تعويضات يومية" → type_cas = "irad_omri_ras_mal"'
            ."\n".'- Si tu trouves "إيراد عمري" SANS "محولا لراسمال" → type_cas = "irad_omri"'
            ."\n".'- Si tu trouves "حادث شغل" أو "حادثة شغل" → type_cas = "irad_omri"'
            ."\n".'- Si tu trouves "غرامة إجبارية" → type_cas = "gharama_ijbariya"'
            ."\n".'- Sinon → type_cas = null'
            // AJOUTE ICI d'autres règles type_cas si besoin
            ."\n\n"

            // ======================================================
            // RÈGLES montant — AJOUTE TES NOUVELLES RÈGLES ICI
            // ======================================================
            // ======================================================
// EXTRACTION SIMPLE DES MONTANTS (NOUVEAU)
// ======================================================
.'=== EXTRACTION DES MONTANTS ==='
."\n".'- Trouve tous les nombres qui ressemblent à des montants (ex: 39336.13, 4,121.66, 23000)'
."\n".'- Supprime les parenthèses (ex: (39336.13) → 39336.13)'
."\n".'- Nettoie les espaces et symboles'
."\n\n"
.'Cherche les montants proches de :'
."\n".'   • راسمال / رأسمال'
."\n".'   • تعويضات يومية'
."\n".'   • مبلغ / قدره / قدرها / درهم'
."\n\n"
.'Règles :'
."\n".'- montant_rasemal_ijmali = montant proche de "راسمال"'
."\n".'- montant_taawidat_youmiya = montant proche de "تعويضات يومية"'
."\n".'- montant_initial = le PLUS GRAND montant trouvé si doute'
."\n".'- Si aucun montant → null'
."\n\n"

            // ======================================================
            // RÈGLES nom_assurance
            // ======================================================
            .'=== RÈGLES POUR nom_assurance ==='
            ."\n".'Cherche parmi ces compagnies et retourne le nom EXACT tel qu\'il apparaît :'
            ."\n".'- شركة التأمين الوفاء'
            ."\n".'- المكتب المركزي'
            ."\n".'- التعاضدية الفلاحية أو المركزية'
            ."\n".'- شركة التأمين أكسا'
            ."\n".'- شركة التأمين الملكية'
            ."\n".'- شركة التأمين النقل'
            ."\n".'- شركة تأمين ارباب النقل'
            ."\n".'- شركة تأمين زوريخ'
            ."\n".'- شركة التأمين سند'
            ."\n".'- شركة تأمين سنلام'
            ."\n".'- شركة التأمين اطلنطا'
            ."\n".-'شركة تأمين اليانز المغرب'
            ."\n".'- Si aucune correspondance → retourne le nom trouvé dans le texte tel quel'
            ."\n\n"

            .'Réponds uniquement par le JSON brut, sans markdown ni texte autour.'
            ."\n\nTexte OCR :\n---\n".$ocrText."\n---\n";
    }

    protected function tryGemini(string $prompt): ?array
    {
        $key = config('services.gemini.key');
        if (! is_string($key) || $key === '') {
            return null;
        }

        $model    = config('services.gemini.model', 'gemini-2.0-flash');
        $url      = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent';

        $response = Http::withoutVerifying()->timeout(120)->post($url.'?key='.$key, [
            'contents'        => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => ['responseMimeType' => 'application/json', 'temperature' => 0.1],
        ]);

        if (! $response->successful()) {
            Log::warning('Gemini API erreur', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
        return is_string($text) ? $this->decodeJsonFromAi($text) : null;
    }

    protected function tryGroq(string $prompt): ?array
    {
        $key = config('services.groq.key');
        if (! is_string($key) || $key === '') {
            return null;
        }

        $response = Http::withoutVerifying()->timeout(120)
            ->withHeaders(['Authorization' => 'Bearer '.$key])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'    => config('assurances.groq_model'),
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.1,
            ]);

        if (! $response->successful()) {
            Log::warning('Groq API erreur', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        $text = $response->json('choices.0.message.content');
        return is_string($text) ? $this->decodeJsonFromAi($text) : null;
    }

    protected function decodeJsonFromAi(string $text): ?array
    {
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = preg_replace('/\s*```\s*$/', '', $text);
        $data = json_decode(trim($text), true);
        return is_array($data) ? $data : null;
    }

    public function validateAndNormalize(array $data): array
    {
        $montantKeys = ['montant_initial', 'montant_rasemal_ijmali', 'montant_taawidat_youmiya'];

        $out = [];
        foreach (self::SCHEMA_KEYS as $key) {
            if (! array_key_exists($key, $data)) {
                $out[$key] = in_array($key, $montantKeys) ? null : '';
                continue;
            }

            if (in_array($key, $montantKeys)) {
                $out[$key] = $this->normalizeMontant($data[$key]);
                continue;
            }

            $out[$key] = is_scalar($data[$key]) ? trim((string) $data[$key]) : '';
        }

        return $out;
    }

    protected function normalizeMontant(mixed $value): ?float
    {
        Log::debug('montant brut', ['value' => $value ?? 'NULL']);

        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return round((float) $value, 2);
        }

        $clean = str_replace(['(', ')'], '', (string) $value);
        $clean = trim(preg_replace('/[^\d.,\-]/', '', $clean));

        // Format européen : 23.372,71
        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d+)?$/', $clean)) {
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        }
        // Format US : 23,372.71
        elseif (preg_match('/^\d{1,3}(,\d{3})+(\.\d+)?$/', $clean)) {
            $clean = str_replace(',', '', $clean);
        }
        // Simple virgule : 23372,71
        elseif (preg_match('/^\d+,\d{1,2}$/', $clean)) {
            $clean = str_replace(',', '.', $clean);
        }

        Log::debug('montant après parsing', ['clean' => $clean, 'result' => is_numeric($clean) ? round((float) $clean, 2) : null]);

        return is_numeric($clean) ? round((float) $clean, 2) : null;
    }
}