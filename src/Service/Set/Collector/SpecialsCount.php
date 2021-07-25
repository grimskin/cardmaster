<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class SpecialsCount
{
    private int $equipmentCount = 0;
    private int $sagaCount = 0;
    private int $auraCount = 0;
    private int $dfcCount = 0;
    private int $classCount = 0;

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

    public function getEquipmentCount(): int
    {
        return $this->equipmentCount;
    }

    public function getSagaCount(): int
    {
        return $this->sagaCount;
    }

    public function getAuraCount(): int
    {
        return $this->auraCount;
    }

    public function getClassCount(): int
    {
        return $this->classCount;
    }

    public function getDfcCount(): int
    {
        return $this->dfcCount;
    }

    private function processCard(CardDefinition $card)
    {
        if ($card->hasSubType(CardDefinition::SBT_EQUIPMENT)) $this->equipmentCount++;
        if ($card->hasSubType(CardDefinition::SBT_AURA)) $this->auraCount++;
        if ($card->hasSubType(CardDefinition::SBT_SAGA)) $this->sagaCount++;
        if ($card->hasSubType(CardDefinition::SBT_CLASS)) $this->classCount++;

        // TODO - find a way to count DFSs without counting cards with "Adventure"
        if ($card->getFaceName() && ($card->getName() !== $card->getFaceName())) $this->dfcCount++;
    }
}
