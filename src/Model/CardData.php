<?php


namespace App\Model;


class CardData
{
    private $name;
    private $faceName;
    private $type = '';

    private $manaCost;
    private $supertypes;
    private $subtypes;
    private $types;
    private $colorIdentity;

    public static function createFromDatum($cardDatum): ?self
    {
        $result = new self();

        $result->name = $cardDatum['name'];
        $result->faceName = $cardDatum['faceName'] ?? '';
        $result->subtypes = $cardDatum['subtypes'] ?? [];
        $result->supertypes = $cardDatum['supertypes'] ?? [];
        $result->type = $cardDatum['type'] ?? [];
        $result->types = $cardDatum['types'] ?? [];
        $result->colorIdentity = $cardDatum['colorIdentity'];
        $result->manaCost = $cardDatum['manaCost'] ?? '';
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

    public function getFaceName()
    {
        return $this->faceName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getSubtypes(): array
    {
        return $this->subtypes;
    }

    public function getColorIdentity()
    {
        return $this->colorIdentity;
    }

    public function getManaCost()
    {
        return $this->manaCost;
    }
}
