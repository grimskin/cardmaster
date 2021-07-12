<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class CreatureStats
{
    private int $maxPower;
    private int $maxToughness;
    private int $maxManaValue;

    private int $creaturesCount = 0;

    private array $powerBreakdown = [];
    private array $toughnessBreakdown = [];
    private array $manaValueBreakdown = [];

    private int $totalPower = 0;
    private int $totalToughness = 0;

    private int $asteriskPowerCount = 0;
    private int $asteriskToughnessCount = 0;
    private int $asteriskBothCount = 0;
    private int $asteriskAnyCount = 0;

    /**
     * @param CardDefinition[] $cards
     */
    public static function fromCards(array $cards): self
    {
        $result = new self();

        foreach ($cards as $card) {
            $result->processCard($card);
        }

        ksort($result->powerBreakdown, SORT_NUMERIC);
        ksort($result->toughnessBreakdown, SORT_NUMERIC);
        ksort($result->manaValueBreakdown, SORT_NUMERIC);

        $result->maxPower = (int) array_key_last($result->powerBreakdown);
        $result->maxToughness = (int) array_key_last($result->toughnessBreakdown);
        $result->maxManaValue = (int) array_key_last($result->manaValueBreakdown);

        $tmp = [];
        for ($i=0; $i<=$result->maxPower; $i++) {
            $tmp[$i] = $result->powerBreakdown[$i] ?? 0;
        }
        $result->powerBreakdown = $tmp;

        $tmp = [];
        for ($i=0; $i<=$result->maxToughness; $i++) {
            $tmp[$i] = $result->toughnessBreakdown[$i] ?? 0;
        }
        $result->toughnessBreakdown = $tmp;

        $tmp = [];
        for ($i=0; $i<=$result->maxManaValue; $i++) {
            $tmp[$i] = $result->manaValueBreakdown[$i] ?? 0;
        }
        $result->manaValueBreakdown = $tmp;

        return $result;
    }

    public function getPtBreakdown(): array
    {
        $count = ($this->maxPower > $this->maxToughness) ? $this->maxPower : $this->maxToughness;

        $result = [];
        for ($i=0; $i<=$count; $i++) {
            $result[$i] = [
                'p' => $this->powerBreakdown[$i] ?? 0,
                't' => $this->toughnessBreakdown[$i] ?? 0,
            ];
        }
        return $result;
    }

    public function getPowerBreakdown(): array
    {
        return $this->powerBreakdown;
    }

    public function getToughnessBreakdown(): array
    {
        return $this->toughnessBreakdown;
    }

    public function getManaValueBreakdown(): array
    {
        return $this->manaValueBreakdown;
    }

    public function getAveragePower(): float
    {
        return $this->totalPower / $this->creaturesCount;
    }

    public function getAverageToughness(): float
    {
        return $this->totalToughness / $this->creaturesCount;
    }

    private function processCard(CardDefinition $card)
    {
        if (!$card->isOfType(CardDefinition::T_CREATURE)) return;

        $this->creaturesCount++;

        if (!$card->isNumericPower() || !$card->isNumericToughness()) {
            if (!$card->isNumericPower() && !$card->isNumericToughness()) $this->asteriskBothCount++;
            if (!$card->isNumericPower()) $this->asteriskPowerCount++;
            if (!$card->isNumericToughness()) $this->asteriskToughnessCount++;
            $this->asteriskAnyCount++;
        } else {
            if (!isset($this->powerBreakdown[$card->getPower()])) $this->powerBreakdown[$card->getPower()] = 0;
            if (!isset($this->toughnessBreakdown[$card->getToughness()])) $this->toughnessBreakdown[$card->getToughness()] = 0;
            if (!isset($this->manaValueBreakdown[$card->getManaValue()])) $this->manaValueBreakdown[$card->getManaValue()] = 0;

            $this->powerBreakdown[$card->getPower()]++;
            $this->toughnessBreakdown[$card->getToughness()]++;
            $this->manaValueBreakdown[$card->getManaValue()]++;

            $this->totalPower += (int) $card->getPower();
            $this->totalToughness += (int) $card->getToughness();
        }
    }
}
