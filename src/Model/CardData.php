<?php


namespace App\Model;


class CardData
{
    private $name;
    private $type = '';

    private $supertypes;
    private $subtypes;
    private $types;
    private $colorIdentity;

    public static function createFromDatum($cardDatum): ?self
    {
        $result = new self();

        $result->name = $cardDatum['name'];
        $result->subtypes = $cardDatum['subtypes'] ?? [];
        $result->supertypes = $cardDatum['supertypes'] ?? [];
        $result->types = $cardDatum['types'] ?? [];
        $result->colorIdentity = $cardDatum['colorIdentity'];
        $result->parseTypes();

        return $result;
    }

    private function parseTypes()
    {
        if (in_array('Land', $this->types) && in_array('Basic', $this->supertypes)) {
            $this->type = CardDefinition::T_BASIC_LAND;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getColorIdentity()
    {
        return $this->colorIdentity;
    }
}
