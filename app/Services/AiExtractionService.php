<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Envoi du texte OCR vers Gemini (prioritaire) ou Groq pour obtenir un JSON structuré.
 */
class AiExtractionService
{
   public const SCHEMA_KEYS = [
    'numero_dossier',
    'numero_jugement',
    'date_jugement',
    'nom_assurance',
    'adresse_assurance',
    'nom_victime',
    'montant_initial',
    'type_cas',
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

        return $this->validateAndNormalize($data);
    }

   protected function buildPrompt(string $ocrText): string
{
    return 'Tu es un expert en dossiers judiciaires marocains (accidents du travail, assurances). '
        .'À partir du texte OCR ci-dessous, extrais UNIQUEMENT un objet JSON valide avec exactement ces clés : '
        .'numero_dossier, numero_jugement, date_jugement, nom_assurance, adresse_assurance, nom_victime (chaînes, "" si inconnu), '
        .'montant_initial (nombre décimal ou null), '
        .'type_cas (string ou null). '
        ."\n\n"

        .'=== RÈGLES POUR type_cas ==='
        ."\n".'- Si tu trouves "إيرادا عمريا سنويا محولا لراسمال" → type_cas = "irad_omri_ras_mal"'
        ."\n".'- Si tu trouves "إيراد عمري" SANS "محولا لراسمال" → type_cas = "irad_omri"'
        ."\n".'- Si tu trouves "غرامة إجبارية" → type_cas = "gharama_ijbariya"'
        ."\n".'- Sinon → type_cas = null'
        ."\n\n"

        .'=== RÈGLES POUR montant_initial ==='
        ."\n".'- Si tu trouves "إيرادا عمريا سنويا محولا لراسمال اجمالي نهائي قدره (X) درهم" SANS تعويضات يومية → montant_initial = X'
        ."\n".'- Si tu trouves "إيرادا عمريا سنويا محولا لراسمال اجمالي نهائي قدره (X) درهم" ET "تعويضات يومية قدرها (Y) درهم" → montant_initial = X + Y'
        ."\n".'- Les montants sont entre parenthèses ex: (23372.71) ou sans parenthèses'
        ."\n".'- Ignorer les espaces et points des milliers, utiliser le point décimal'
        ."\n".'- Si aucun montant trouvé → null'
        ."\n\n"

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
."\n".'- شرمة التأمين اطلنطا'
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

    $model = config('services.gemini.model', 'gemini-2.0-flash');
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent';

    $response = Http::withoutVerifying()->timeout(120)->post($url.'?key='.$key, [
        'contents' => [
            ['parts' => [['text' => $prompt]]],
        ],
        'generationConfig' => [
            'responseMimeType' => 'application/json',
            'temperature' => 0.1,
        ],
    ]);

    if (! $response->successful()) {
        Log::warning('Gemini API erreur', ['status' => $response->status(), 'body' => $response->body()]);
        return null;
    }

    $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
    if (! is_string($text)) {
        return null;
    }

    return $this->decodeJsonFromAi($text);
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
            'model' => config('assurances.groq_model'),
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.1,
        ]);

    if (! $response->successful()) {
        Log::warning('Groq API erreur', ['status' => $response->status(), 'body' => $response->body()]);
        return null;
    }

    $text = $response->json('choices.0.message.content');
    if (! is_string($text)) {
        return null;
    }

    return $this->decodeJsonFromAi($text);
}
    protected function decodeJsonFromAi(string $text): ?array
    {
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = preg_replace('/\s*```\s*$/', '', $text);
        $data = json_decode(trim($text), true);

        return is_array($data) ? $data : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function validateAndNormalize(array $data): array
    {
        $out = [];
        foreach (self::SCHEMA_KEYS as $key) {
            if (! array_key_exists($key, $data)) {
                $out[$key] = $key === 'montant_initial' ? null : '';

                continue;
            }

            if ($key === 'montant_initial') {
                $out[$key] = $this->normalizeMontant($data[$key]);

                continue;
            }

            $out[$key] = is_scalar($data[$key]) ? trim((string) $data[$key]) : '';
        }

        return $out;
    }

    protected function normalizeMontant(mixed $value): ?float
{
    if ($value === null || $value === '') {
        return null;
    }
    if (is_numeric($value)) {
        return round((float) $value, 2);
    }

    // Enlever les parenthèses
    $clean = str_replace(['(', ')'], '', (string) $value);
    // Enlever tout sauf chiffres, point, virgule
    $clean = preg_replace('/[^\d.,\-]/', '', $clean);
    // Remplacer virgule par point
    $clean = str_replace(',', '.', $clean);

    return is_numeric($clean) ? round((float) $clean, 2) : null;
}
}
