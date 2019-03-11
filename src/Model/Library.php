<?php


namespace App\Model;


class Library
{
    private const POSITION_TOP = 0;

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

        return $this->drawFromPosition(self::POSITION_TOP);
    }

    public function drawByName(string $cardName): ?CardDefinition
    {
        /** @var CardDefinition $card */
        foreach ($this->cards as $index=>$card) {
            if ($card->getName() == $cardName) {
                $position = $index;
                break;
            }
        }

        if (!isset($position)) {
            return null;
        }

        return $this->drawFromPosition($position);
    }

    public function drawFromPosition(int $position): ?CardDefinition
    {
        if (!isset($this->cards[$position])) {
            return null;
        }

        $result = $this->cards[$position];

        unset($this->cards[$position]);

        $this->cards = array_values($this->cards);

        return $result;
    }

    public function drawHand(int $handSize): array
    {
        $result = [];

        for ($i=0;$i<$handSize;$i++) {
            $result[] = $this->draw();
        }

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
