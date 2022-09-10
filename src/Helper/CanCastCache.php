<?php


namespace App\Helper;


use App\Domain\ManaCost;
use App\Domain\ManaCostItem;
use App\Model\CardDefinition;

class CanCastCache
{
    /**
     * @var bool[]
     */
    private array $cache = [];

    /**
     * @param ManaCost $cost
     * @param CardDefinition[] $cardDefinitions
     * @return bool|null
     */
    public function getResult(ManaCost $cost, array $cardDefinitions): ?bool
    {
        $key = $this->getLandsString($cardDefinitions) . '|' . $this->getCostString($cost);

        return $this->cache[$key] ?? null;
    }

    private function getCostString(ManaCost $cost): string
    {
        return implode('.', array_map(function (ManaCostItem $item) {
            return $item->jsonSerialize();
        }, $cost->jsonSerialize()));
    }

    /**
     * @param CardDefinition[] $cardDefinitions
     * @return string
     */
    private function getLandsString(array $cardDefinitions): string
    {
        $lands = array_filter($cardDefinitions, function (CardDefinition $item) {
            return $item->isLand();
        });

        $manaArr = [];
        foreach ($lands as $land) {
            $manaArr[] = implode('', $land->getColorIdentity());
        }

        return implode('.', $manaArr);
    }

    /**
     * @param ManaCost $cost
     * @param CardDefinition[] $cardDefinitions
     * @param bool $result
     * @return void
     */
    public function saveResult(ManaCost $cost, array $cardDefinitions, bool $result): void
    {
        $key = $this->getLandsString($cardDefinitions) . '|' . $this->getCostString($cost);

        $this->cache[$key] = $result;
    }
}
