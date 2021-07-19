<?php


namespace App\Service\Set;


use App\Model\CardData;
use App\Model\CardDefinition;
use App\Service\Set\Collector\CreatureStats;
use App\Service\Set\Collector\CreatureTypes;
use App\Service\Set\Collector\LegendaryCounter;
use App\Service\Set\Collector\RandomFacts;

class StatsCollector
{
    private array $data;
    /**
     * @var CardDefinition[]
     */
    private array $cards;
    private ?int $uniqueCardsCount = null;
    private ?CreatureTypes $creatureTypesStats = null;
    private ?CreatureStats $creatureStatsStats = null;
    private ?LegendaryCounter $legendaryCounter = null;
    private ?RandomFacts $randomFacts = null;

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

    public function getCreatureTypesStats(): CreatureTypes
    {
        if (!$this->creatureTypesStats) $this->creatureTypesStats = CreatureTypes::fromCards($this->cards);

        return $this->creatureTypesStats;
    }

    public function getCreatureStatsStats(): CreatureStats
    {
        if (!$this->creatureStatsStats) $this->creatureStatsStats = CreatureStats::fromCards($this->cards);

        return $this->creatureStatsStats;
    }

    public function getLegendaryCounts(): LegendaryCounter
    {
        if (!$this->legendaryCounter) $this->legendaryCounter = LegendaryCounter::fromCards($this->cards);

        return $this->legendaryCounter;
    }

    public function getRandomFacts(): RandomFacts
    {
        if ($this->randomFacts) return $this->randomFacts;

        $this->randomFacts = RandomFacts::fromCards($this->cards);
        $this->randomFacts->setCreatureStats($this->getCreatureStatsStats());;

        return $this->randomFacts;
    }

    public function getSetName(): string
    {
        return $this->data['name'];
    }
}
