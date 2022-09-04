<?php


namespace App\Domain;


use JsonSerializable;
use ReturnTypeWillChange;

class ManaCost implements JsonSerializable
{
    private string $manaCostString = '';

    /** @var ManaCostItem[] */
    private array $manaItems = [];

    private ?array $costVariants;

    public function __construct(string $manaCostString)
    {
        $this->manaCostString = $manaCostString;
        $this->costVariants = null;
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

    public function getCostVariants(): array
    {
        if (null === $this->costVariants) {
            $this->costVariants = $this->calculateCostVariants($this->manaItems);
        }

        return $this->costVariants;
    }

    private function calculateCostVariants(array $manaItems): array
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
                $result[] = [...$variant, ...$leftoverVariant];
            }
        }

        return $result;
    }

    public function hasX(): bool
    {
        foreach ($this->manaItems as $item) {
            if ($item->isX()) return true;
        }

        return false;
    }

    public function __toString()
    {
        return $this->manaCostString;
    }

    public function jsonSerialize(): array
    {
        return $this->manaItems;
    }
}
