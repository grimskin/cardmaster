<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class LegendaryCounter
{
    private const RARITIES = [
        'mythic' => 'Mythic',
        'rare' => 'Rare',
        'uncommon' => 'Uncommon',
        // there were no common legendaries for a long time
    ];

    private array $breakdown = [];
    private int $total = 0;

    /**
     * @param CardDefinition[] $cards
     */
    public static function fromCards(array $cards): self
    {
        $result = new self();

        foreach ($cards as $card) {
            $result->processCard($card);
        }

        uasort($result->breakdown, function($a, $b) { return $b['Total'] <=> $a['Total']; });
        $result->initBreakdownForType('');

        foreach ($result->breakdown as $stats) {
            foreach ($stats as $rarity=>$count) {
                $result->breakdown[''][$rarity] += $count;
            }
        }

        return $result;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getBreakdown(): array
    {
        return $this->breakdown;
    }

    private function processCard(CardDefinition $card)
    {
        if (!$card->isLegendary()) return;

        $this->total++;

        $rarityName = self::RARITIES[$card->getRarity()];

        foreach ($card->getTypes() as $type) {
            $this->initBreakdownForType($type);

            $this->breakdown[$type][$rarityName]++;
            $this->breakdown[$type]['Total']++;
        }
    }

    private function initBreakdownForType(string $type)
    {
        if (isset($this->breakdown[$type])) return;

        $this->breakdown[$type] = self::RARITIES;
        $this->breakdown[$type] = array_flip($this->breakdown[$type]);
        $this->breakdown[$type]['Total'] = 0;

        foreach ($this->breakdown[$type] as $key=>$value) {
            $this->breakdown[$type][$key] = 0;
        }
    }
}
