<?php


namespace App\Service;


class DeckFetcher
{

    public function fetchDeck(string $deckUrl)
    {
        // https://www.mtggoldfish.com/deck/arena_download/3013464
        // <textarea class='copy-paste-box'>Deck
        $html = $this->downloadDeckPage($deckUrl);

        $cards = $this->getCardsSection($html);

        return $cards;
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
        // TODO use proper http client when I fix composer
        return file_get_contents($deckUrl);
    }

}
