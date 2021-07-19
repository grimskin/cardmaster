<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class CreatureTypes
{
    private array $types = [];
    private array $oneOffs = [];
    private array $typesOnCreature = [];

    /**
     * @param CardDefinition[] $cards
     */
    public static function fromCards(array $cards): self
    {
        $result = new self();

        foreach ($cards as $card) {
            $result->processCard($card);
        }

        asort($result->types, SORT_NUMERIC);
        $result->types = array_reverse($result->types, true);

        foreach ($result->types as $type=>$count) {
            if ($count === 1) $result->oneOffs[] = $type;
        }
        asort($result->oneOffs, SORT_NATURAL);
        ksort($result->typesOnCreature, SORT_NUMERIC);
        $result->typesOnCreature = array_reverse($result->typesOnCreature, true);

        return $result;
    }

    public function getOneOffs(): array
    {
        return $this->oneOffs;
    }

    public function getStats(): array
    {
        return $this->types;
    }

    public function getTypesOnCreature(): array
    {
        return $this->typesOnCreature;
    }

    private function processCard(CardDefinition $card)
    {
        if (!$card->isOfType(CardDefinition::T_CREATURE)) return;

        foreach ($card->getSubTypes() as $subType) {
            if (isset($this->types[$subType])) {
                $this->types[$subType]++;
            } else {
                $this->types[$subType] = 1;
            }
        }

        $typesCount = count($card->getSubTypes());
        if (!isset($this->typesOnCreature[$typesCount])) $this->typesOnCreature[$typesCount] = 0;
        $this->typesOnCreature[$typesCount]++;
    }
}
