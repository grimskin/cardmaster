<?php


namespace App\Model;


class Library
{
    /**
     * @var DeckDefinition
     */
    private $definition;
    private $cards = [];

    public static function make(DeckDefinition $deck): self
    {
        $result = new self();

        $result->definition = $deck;
        $result->cards = $deck->getCards();

        return $result;
    }

    public function draw(): ?CardDefinition
    {
        if (!count($this->cards)) {
            return null;
        }

        $result = $this->cards[count($this->cards) - 1];

        unset($this->cards[count($this->cards) - 1]);

        return $result;
    }

    public function reset()
    {
        $this->cards = $this->definition->getCards();
    }

    public function shuffle(int $resultAmount = 9999)
    {
        $cards = $this->cards;
        $library = [];

        $cardsCount = count($cards);

        while ($cardsCount > 1 && --$resultAmount) {
            $position = mt_rand(0, $cardsCount-1);

            $library[] = $cards[$position];
            $cards[$position] = $cards[$cardsCount-1];
            unset($cards[$cardsCount-1]);

            $cardsCount--;
        }

        $library[] = $cards[0];

        $this->cards = $library;
    }
}
