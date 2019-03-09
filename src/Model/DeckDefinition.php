<?php

namespace App\Model;

class DeckDefinition
{
    private $cards = [];

    public function addCards(CardDefinition $card, int $amount)
    {
        for ($i=0; $i<$amount; $i++) {
            $this->addCard($card);
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function addCard(CardDefinition $card)
    {
        $this->cards[] = $card;
    }

    public function getCardsAmount(): int
    {
        return count($this->cards);
    }
}
