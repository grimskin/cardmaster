<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class LegendaryCounter
{
    private array $counts = [];
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

        asort($result->counts, SORT_NUMERIC);
        $result->counts = array_reverse($result->counts);

        return $result;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getCounts(): array
    {
        return $this->counts;
    }

    private function processCard(CardDefinition $card)
    {
        if (!$card->isLegendary()) return;

        $this->total++;

        foreach ($card->getTypes() as $type) {
            if (!isset($this->counts[$type])) $this->counts[$type] = 0;

            $this->counts[$type]++;
        }
    }
}
