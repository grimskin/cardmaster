<?php


namespace App\Service;


use App\Helper\MtgGoldfishUrl;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

class DeckFetcher
{
    public function fetchDeck(string $deckUrl): array
    {
        $url = MtgGoldfishUrl::makeImportUrl($deckUrl);
        try {
            $html = $this->downloadDeckPage($url);
        } catch (Exception $e) {
            return [];
        }

        return $this->getCardsSection($html);
    }

    private function getCardsSection(string $html): array
    {
        $html = substr($html, strpos($html, '<textarea class=\'copy-paste-box\'>') + strlen('<textarea class=\'copy-paste-box\'>'));
        $html = substr($html, strpos($html, 'Deck') + strlen('Deck'));
        $html = substr($html, 0, strpos($html, 'Sideboard'));

        $cards = explode("\n", $html);
        $cards = array_map(function(string $item) {
            [$amount, $cardName] = explode(' ', $item, 2);
            return ['amount' => $amount, 'card_name' => htmlspecialchars_decode($cardName, ENT_QUOTES)];
        }, array_filter($cards));

        return $cards;
    }

    private function downloadDeckPage(string $deckUrl): string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $deckUrl);
        return $response->getContent();
    }
}
