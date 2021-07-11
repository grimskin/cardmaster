<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class CreatureTypes
{
    private array $types = [];
    private array $oneOffs = [];

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
        $result->types = array_reverse($result->types);

        foreach ($result->types as $type=>$count) {
            if ($count === 1) $result->oneOffs[] = $type;
        }
        asort($result->oneOffs, SORT_NATURAL);

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
    }
}
