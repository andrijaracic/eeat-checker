<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ArticleFetcher
{
    public function fetch(string $url): array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'LaravelArticleFetcher/1.0'
            ])->timeout(10)->get($url);

            if (!$response->successful()) {
                throw new \Exception('Neuspešno preuzimanje stranice.');
            }

            $html = $response->body();

            // TITLE
            preg_match('/<title>(.*?)<\/title>/i', $html, $matches);
            $title = $matches[1] ?? 'Nepoznat naslov';

            // AUTHOR
            preg_match('/<meta\s+name=["\']author["\']\s+content=["\'](.*?)["\']\s*\/?>/i', $html, $matches);
            $author = $matches[1] ?? 'Nepoznat autor';

            // PUBLISHED DATE
            preg_match('/<meta\s+(?:property|name)=["\'](?:article:published_time|date)["\']\s+content=["\'](.*?)["\']\s*\/?>/i', $html, $matches);
            $publishedAt = $matches[1] ?? null;

            // CONTENT - sve iz <body> filtrirano po tekstualnim tagovima
            if (preg_match('/<body.*?>(.*?)<\/body>/is', $html, $matches)) {
                $bodyHtml = $matches[1];
                $content = $this->extractTextualContent($bodyHtml);
            } else {
                $content = 'Sadržaj nije pronađen.';
            }

            return [
                'title' => $title,
                'author' => $author,
                'published_at' => $publishedAt,
                'content' => $content
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    // Funkcija koja uzima sve tekstualne tagove i spaja ih
    private function extractTextualContent(string $html): string
    {
        // Parsiramo sve tekstualne tagove
        preg_match_all('/<(h[1-3]|p|span|strong|em|li|ul|ol)[^>]*>(.*?)<\/\1>/is', $html, $matches);

        $textBlocks = [];
        foreach ($matches[2] as $block) {
            $clean = trim(strip_tags($block));

            // 1️⃣ Odbacujemo prekratke linije
            if (strlen($clean) < 50) continue;

            // 2️⃣ Odbacujemo linije koje su datumi ili tipične „gluposti“
            if (preg_match('/\b\d{1,2}\.\s*(jan|feb|mar|apr|maj|jun|jul|avg|sep|okt|nov|dec|january|february|march|april|may|june|july|august|september|october|november|december)\b/i', $clean)) continue;
            if (preg_match('/\b(Najnovije|Podeli|Komentari|Oglas|Marketing|Impresum|Kontakt)\b/i', $clean)) continue;

            $textBlocks[] = $clean;
        }

        return implode("\n\n", $textBlocks);
    }

}
