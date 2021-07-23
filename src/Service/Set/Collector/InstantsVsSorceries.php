<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class InstantsVsSorceries
{
    private array $manaBreakdown = [
        CardDefinition::T_INSTANT => [],
        CardDefinition::T_SORCERY => [],
    ];
    private array $counts = [
        CardDefinition::T_INSTANT => 0,
        CardDefinition::T_SORCERY => 0,
    ];
    private int $maxManaValue = 0;
    private array $xCount = [
        CardDefinition::T_INSTANT => 0,
        CardDefinition::T_SORCERY => 0,
    ];

    /**
     * @param CardDefinition[] $cards
     */
    public static function fromCards(array $cards): self
    {
        $result = new self();

        foreach ($cards as $card) {
            $result->processCard($card);
        }

        $manaValues = [];
        for ($i=0; $i<=$result->getMaxManaValue(); $i++) {
            $manaValues[CardDefinition::T_INSTANT][$i] = $result->manaBreakdown[CardDefinition::T_INSTANT][$i] ?? 0;
            $manaValues[CardDefinition::T_SORCERY][$i] = $result->manaBreakdown[CardDefinition::T_SORCERY][$i] ?? 0;
        }
        $result->manaBreakdown = $manaValues;

        return $result;
    }

    public function getMaxManaValue(): int
    {
        return $this->maxManaValue;
    }

    public function getManaBreakdownSorceries(): array
    {
        return $this->manaBreakdown[CardDefinition::T_SORCERY];
    }

    public function getManaBreakdownInstants(): array
    {
        return $this->manaBreakdown[CardDefinition::T_INSTANT];
    }

    public function getCountInstants(): int
    {
        return $this->counts[CardDefinition::T_INSTANT];
    }

    public function getCountSorceries(): int
    {
        return $this->counts[CardDefinition::T_SORCERY];
    }

    public function getXCountInstants(): int
    {
        return $this->xCount[CardDefinition::T_INSTANT];
    }

    public function getXCountSorceries(): int
    {
        return $this->xCount[CardDefinition::T_SORCERY];
    }

    private function processCard(CardDefinition $card)
    {
        if (!$card->isOfType(CardDefinition::T_INSTANT) && !$card->isOfType(CardDefinition::T_SORCERY)) return;

        $type = $card->isOfType(CardDefinition::T_INSTANT) ? CardDefinition::T_INSTANT : CardDefinition::T_SORCERY;

        $this->counts[$type]++;
        if ($card->getManaCost()->hasX()) $this->xCount[$type]++;

        if (!isset($this->manaBreakdown[$type][$card->getManaValue()])) $this->manaBreakdown[$type][$card->getManaValue()] = 0;

        $this->manaBreakdown[$type][$card->getManaValue()]++;

        if ($card->getManaValue() > $this->maxManaValue) $this->maxManaValue = $card->getManaValue();
    }
}
