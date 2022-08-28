<?php


namespace App\Model;


class Library
{
    private const POSITION_TOP = 0;

    private DeckDefinition $definition;
    private array $cards = [];

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

    public function putOnTop(CardDefinition $card): void
    {
        array_unshift($this->cards, $card);
    }

    public function putOnBottom(CardDefinition $card): void
    {
        $this->cards[] = $card;
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

    public function reset(): void
    {
        $this->cards = $this->definition->getCards();
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }
}
