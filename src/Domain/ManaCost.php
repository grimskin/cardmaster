<?php


namespace App\Domain;


use JsonSerializable;

class ManaCost implements JsonSerializable
{
    private $manaCostString = '';

    /** @var ManaCostItem[]  */
    private $manaItems = [];

    private $costVariants;

    public function __construct(string $manaCostString)
    {
        $this->manaCostString = $manaCostString;
        $this->parseManaCost();
    }

    private function parseManaCost()
    {
        $cost = $this->manaCostString;

        while ($pos = strpos($cost, '}')) {
            $this->manaItems[] = new ManaCostItem(substr($cost, 0, $pos+1));
            $cost = substr($cost, $pos+1);
        }
    }

    public function getCostVariants()
    {
        if (null === $this->costVariants) {
            $this->costVariants = $this->calculateCostVariants($this->manaItems);
        }

        return $this->costVariants;
    }

    private function calculateCostVariants(array $manaItems)
    {
        $result = [];

        /** @var ManaCostItem $item */
        $item = array_pop($manaItems);

        if (!count($manaItems)) {
            return $item->getItemVariants();
        }

        $leftoverVariants = $this->calculateCostVariants($manaItems);

        foreach ($item->getItemVariants() as $variant) {
            foreach ($leftoverVariants as $leftoverVariant) {
                $result[] = array_merge($variant, $leftoverVariant);
            }
        }

        return $result;
    }

    public function __toString()
    {
        return $this->manaCostString;
    }

    public function jsonSerialize()
    {
        return $this->manaItems;
    }
}
