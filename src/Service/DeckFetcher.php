<?php


namespace App\Service;


use App\Helper\MtgGoldfishUrl;
use Symfony\Component\HttpClient\HttpClient;

class DeckFetcher
{
    public function fetchDeck(string $deckUrl)
    {
        $url = MtgGoldfishUrl::makeImportUrl($deckUrl);
        $html = $this->downloadDeckPage($url);

        return $this->getCardsSection($html);
    }

    private function getCardsSection(string $html): array
    {
        $html = substr($html, strpos($html, '<textarea class=\'copy-paste-box\'>Deck') + strlen('<textarea class=\'copy-paste-box\'>Deck'));
        $html = substr($html, 0, strpos($html, 'Sideboard'));

        $cards = explode("\n", $html);
        $cards = array_map(function(string $item) {
            [$amount, $cardName] = explode(' ', $item, 2);
            return ['amount' => $amount, 'card_name' => $cardName];
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
