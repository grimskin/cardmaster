<?php


namespace App\Service\Set;


use App\Model\CardData;
use App\Model\CardDefinition;

class StatsCollector
{
    private array $data;
    /**
     * @var CardDefinition[]
     */
    private array $cards;
    private ?int $uniqueCardsCount = null;

    public function addCards(array $data)
    {
        $processedNames = [];

        /** @var CardData $card */
        foreach ($data['cards'] as $card) {
            $filterName = $card->getFaceName() ?: $card->getName();

            if (isset($processedNames[$filterName])) continue;
            $cardDefinition = new CardDefinition();
            $cardDefinition->absorbData($card);

            if ($cardDefinition->isBasicLand()) continue;

            $processedNames[$filterName] = $filterName;
            $this->cards[] = $cardDefinition;
        }

        unset($data['cards']);
        $this->data = $data;
    }

    public function getCardsCount(): int
    {
        return count($this->cards);
    }

    public function getUniqueCardsCount(): int
    {
        if ($this->uniqueCardsCount) return $this->uniqueCardsCount;

        $numbers = [];

        foreach ($this->cards as $card) {
            $numbers[$card->getNumber()] = $card->getNumber();
        }
        $this->uniqueCardsCount = count($numbers);

        return $this->uniqueCardsCount;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getSetName(): string
    {
        return $this->data['name'];
    }
}
