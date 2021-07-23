<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;
use JetBrains\PhpStorm\Pure;

class RandomFacts
{
    private ?CreatureStats $creatureStats;
    private ?InstantsVsSorceries $instantsVsSorceries;

    private array $longestNames = [];
    private int $longestNameLength = 0;

    /**
     * @param CardDefinition[] $cards
     */
    public static function fromCards(array $cards): self
    {
        $result = new self();

        foreach ($cards as $card) {
            $result->processCard($card);
        }

        return $result;
    }

    private function processCard(CardDefinition $cardDefinition)
    {
        $cardName = $cardDefinition->getFaceName() ?: $cardDefinition->getName();
        if (mb_strlen($cardName) > $this->longestNameLength) {
            $this->longestNames = [ $cardName ];
            $this->longestNameLength = mb_strlen($cardName);
        } elseif (mb_strlen($cardName) === $this->longestNameLength) {
            $this->longestNames[] = $cardName;
        }
    }

    public function setCreatureStats(CreatureStats $creatureStats)
    {
        $this->creatureStats = $creatureStats;
    }

    public function setInstantsVsSorceries(?InstantsVsSorceries $instantsVsSorceries): void
    {
        $this->instantsVsSorceries = $instantsVsSorceries;
    }

    public function countInstantsWithX(): int
    {
        return $this->instantsVsSorceries->getXCountInstants();
    }

    public function countSorceriesWithX(): int
    {
        return $this->instantsVsSorceries->getXCountSorceries();
    }

    public function getLongestNames(): array
    {
        return $this->longestNames;
    }

    public function getLongestNameLength(): int
    {
        return $this->longestNameLength;
    }

    #[Pure]
    public function getAsteriskAnyCount(): int
    {
        return $this->creatureStats?->getAsteriskAnyCount();
    }
}
