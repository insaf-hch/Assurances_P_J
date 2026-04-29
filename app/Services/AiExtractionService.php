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
protected function cleanOcrText(string $text): string
{
    // Supprime caractères bidi invisibles (cause principale du 23/8/1)
    $text = preg_replace('/[\x{200E}\x{200F}\x{202A}-\x{202E}\x{2066}-\x{2069}]/u', '', $text);

    // Convertit chiffres arabes → latins
    $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $latin  = ['0','1','2','3','4','5','6','7','8','9'];
    $text = str_replace($arabic, $latin, $text);

    // Supprime parasites OCR
    $text = preg_replace('/[*#|]/u', '', $text);

    // Normalise espaces
    $text = preg_replace('/[ \t]+/', ' ', $text);
    $text = preg_replace('/\n{3,}/', "\n\n", $text);

    return trim($text);
}
   public function extractStructured(string $ocrText): array
{
    $ocrText = $this->cleanOcrText($ocrText);
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

    // ✅ CORRECTION : Détection plus intelligente des montants
    $montantsDansOCR = $this->detectMontantsInOCR($ocrText);
    
    if (!$montantsDansOCR['has_any_montant']) {
        Log::info('Aucun montant détecté dans OCR', ['debug' => $montantsDansOCR]);
        $data['montant_initial'] = null;
        $data['montant_rasemal_ijmali'] = null;
        $data['montant_taawidat_youmiya'] = null;
    } else {
        // Calcul normal
        $rasemal = (float)($data['montant_rasemal_ijmali'] ?? 0);
        $taawidat = (float)($data['montant_taawidat_youmiya'] ?? 0);
        
        if ($rasemal > 0 || $taawidat > 0) {
            $data['montant_initial'] = $rasemal + $taawidat;
        } elseif ($montantsDansOCR['found_numbers'] > 0) {
            // Si l'IA n'a pas extrait les montants mais que l'OCR contient des nombres
            Log::warning('OCR contient des nombres mais IA na pas extrait de montants', [
                'numbers_found' => $montantsDansOCR['sample_numbers']
            ]);
        }
    }

    return $data;
}

/**
 * Détection intelligente des montants dans l'OCR
 */
protected function detectMontantsInOCR(string $text): array
{
    // Mots-clés de montant (plus complets)
    $montantKeywords = [
        'راسمال', 'رأسمال', 'تعويضات', 'درهم', 'قدره', 'قدرها', 'مبلغ',
        'المبلغ', 'إجمالي', 'جملة', 'جملته', 'مجموع', 'الإجمالي'
    ];
    
    $hasKeyword = false;
    foreach ($montantKeywords as $kw) {
        if (str_contains($text, $kw)) {
            $hasKeyword = true;
            break;
        }
    }
    
    // Cherche des nombres qui ressemblent à des montants (avec décimales ou séparateurs)
    $numberPattern = '/\b[\d,.]{4,}\b/'; // Nombres avec au moins 4 chiffres
    preg_match_all($numberPattern, $text, $matches);
    $potentialNumbers = array_unique($matches[0] ?? []);
    
    // Filtre les vrais montants (probablement > 1000)
    $realNumbers = array_filter($potentialNumbers, function($num) {
        $clean = str_replace(['.', ','], '', $num);
        return is_numeric($clean) && (int)$clean > 1000;
    });
    
    return [
        'has_any_montant' => $hasKeyword || count($realNumbers) > 0,
        'has_keyword' => $hasKeyword,
        'found_numbers' => count($realNumbers),
        'sample_numbers' => array_slice($realNumbers, 0, 3)
    ];
}
// Ajoute cette méthode dans la classe
protected function normalizeArabicNumerals(string $text): string
{
    $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $latin  = ['0','1','2','3','4','5','6','7','8','9'];
    return str_replace($arabic, $latin, $text);
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
        .'À partir du texte OCR ci-dessous, extrais UNIQUEMENT un objet JSON valide avec ces clés : '
        .'numero_dossier, numero_jugement, date_jugement, nom_assurance, adresse_assurance, '
        .'nom_victime, montant_initial, montant_rasemal_ijmali, montant_taawidat_youmiya, type_cas, type_malaf.'
        ."\n\n"

        .'=== RÈGLES GÉNÉRALES ==='
        ."\n".'- Ne jamais inventer une valeur — si tu n\'es pas sûr → null ou ""'
        ."\n".'- Copie les chiffres EXACTEMENT, sans ajouter ni supprimer de zéros'
        ."\n\n"

        .'=== numero_dossier ==='
        ."\n".'- Cherche UNIQUEMENT la ligne contenant "ملف رقم" ou "ملف عدد"'
        ."\n".'- Le numéro a toujours 3 parties ex: 376/1502/2023'
        ."\n".'- Ne jamais confondre avec une date ou un autre numéro dans le texte'
        ."\n".'- Si non trouvé clairement → retourne ""'
        ."\n\n"

        .'=== numero_jugement ==='
        ."\n".'- Cherche après "حكم عدد" ou "حكم رقم"'
        ."\n".'- Si non trouvé → retourne ""'
        ."\n\n"

        .'=== nom_assurance ==='
        ."\n".'- Cherche après "المدعى عليها" ou "شركة التأمين" — souvent entre guillemets'
        ."\n".'- Ne jamais retourner "شركة التأمين" seul sans le nom complet'
."\n".'- Si tu ne peux pas identifier le nom exact → retourne ""'
        ."\n".'- Si tu vois "الوفاء"        → "شركة التأمين الوفاء"'
        ."\n".'- Si tu vois "أكسا"           → "شركة التأمين أكسا"'
        ."\n".'- Si tu vois "سند"            → "شركة التأمين سند"'
        ."\n".'- Si tu vois "سنلام"          → "شركة تأمين سنلام"'
        ."\n".'- Si tu vois "زوريخ"          → "شركة تأمين زوريخ"'
        ."\n".'- Si tu vois "اطلنطا"         → "شركة التأمين اطلنطا"'
        ."\n".'- Si tu vois "الملكية"        → "شركة التأمين الملكية"'
        ."\n".'- Si tu vois "اليانز"         → "شركة تأمين اليانز المغرب"'
        ."\n".'- Si tu vois "المكتب المركزي" → "المكتب المركزي"'
        ."\n".'- Si tu vois "تعاضدية"        → "التعاضدية الفلاحية أو المركزية"'
        ."\n\n"

        .'=== MONTANTS ==='
."\n".'- IMPORTANT : Si les mots "راسمال" ou "تعويضات يومية" ou "درهم" ou "قدره" ne sont PAS dans le texte OCR → retourne null pour TOUS les montants'
."\n".'- Ne jamais deviner ou utiliser des montants non présents dans le texte'
."\n".'- montant_rasemal_ijmali  = montant IMMÉDIATEMENT après "لراسمال" ou "راسمال"'
."\n".'- montant_taawidat_youmiya = montant IMMÉDIATEMENT après "تعويضات يومية"'
."\n".'- montant_initial = somme des deux si présents, sinon montant principal'
."\n\n"

        .'=== type_cas ==='
        ."\n".'- "إيرادا عمريا سنويا محولا لراسمال" + "تعويضات يومية" → "masdar_total_taawidat"'
        ."\n".'- "إيرادا عمريا سنويا محولا لراسمال" seul → "irad_omri_ras_mal"'
        ."\n".'- "إيراد عمري" seul → "irad_omri"'
        ."\n".'- "حادث شغل" ou "حادثة شغل" → "irad_omri"'
        ."\n".'- "غرامة إجبارية" → "gharama_ijbariya"'
        ."\n".'- Sinon → null'
        ."\n\n"

        .'=== MONTANTS ==='
        ."\n".'- montant_rasemal_ijmali  = montant après "لراسمال" ou "راسمال" ou "رأسمال"'
        ."\n".'- montant_taawidat_youmiya = montant après "تعويضات يومية"'
        ."\n".'- montant_initial = somme des deux si présents, sinon le montant principal du jugement'
        ."\n".'- Supprime parenthèses : (21647.54) → 21647.54'
        ."\n".'- Si aucun montant clairement lisible → null'
        ."\n\n"

        .'Réponds UNIQUEMENT par le JSON brut, sans markdown.'
        ."\n\nTexte OCR :\n---\n".$ocrText."\n---\n";
}
// Ajoutez cette méthode temporaire pour voir ce que l'IA répond vraiment
public function debugExtraction(string $ocrText): void
{
    $ocrText = $this->cleanOcrText($ocrText);
    $prompt = $this->buildPrompt($ocrText);
    
    Log::info('=== DÉBUT PROMPT ===');
    Log::info(substr($prompt, 0, 1500));
    Log::info('=== FIN PROMPT ===');
    
    // Test Gemini
    $result = $this->tryGemini($prompt);
    Log::info('RÉPONSE GEMINI:', ['result' => $result]);
    
    if (!$result) {
        $result = $this->tryGroq($prompt);
        Log::info('RÉPONSE GROQ:', ['result' => $result]);
    }
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

            $out[$key] = is_scalar($data[$key]) 
    ? trim($this->normalizeArabicNumerals((string) $data[$key])) 
    : '';
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