<?php

namespace App\Model;

class DeckDefinition
{
    private array $cards = [];

    public function addCards(CardDefinition $card, int $amount): void
    {
        for ($i=0; $i<$amount; $i++) {
            $this->addCard($card);
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function addCard(CardDefinition $card): void
    {
        $this->cards[] = $card;
    }

    public function getCardsAmount(): int
    {
        return count($this->cards);
    }

    public function getLibrary(): Library
    {
        return Library::make($this);
    }
}
