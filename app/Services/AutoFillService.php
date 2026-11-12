<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AutoFillService
{
    public static function fill($url)
    {
        if (empty($url)) {
            return null;
        }

        $systemPrompt = <<<'EOT'
            Extraia os seguintes dados estruturados do conteúdo HTML acadêmico fornecido:
                - title (título completo, incluindo subtítulo quando houver)
                - year
                - authors (como uma lista de objetos: forename, lastname)
                - type (Tese, Dissertação, Periódico)
                - institution (universidade, se tese ou dissertação)
                - program
                - periodical (se artigo)
                - doi (se disponível)
                - language (idioma do documento)
                - city
                - keywords (palavras-chave em português, se disponíveis, separadas por ponto e vírgula)
                - abstract (resumo em português, se disponível)

            Responda em JSON com essa estrutura:
            {
                "title": "",
                "year": "",
                "authors": [
                    { "forename": "", "lastname": "" }
                ],
                "type": "",
                "institution": "",
                "program": "",
                "periodical": "",
                "doi": "",
                "language": "",
                "city": "",
                "keywords": "",
                "abstract": ""
            }
        EOT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('DEEPSEEK_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => strip_tags($url)],
            ],
            'temperature' => 0.1,
        ]);

        return $response->json();
    }
}
