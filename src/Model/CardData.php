<?php


namespace App\Model;


class CardData
{
    private string $name;
    private string $faceName;
    private string $type = '';

    private array $keywords;
    private string $manaCost;
    private string $manaValue;
    private string $number;
    private string $rarity;
    private array $supertypes;
    private array $subtypes;
    private array $types;
    private array $colors;
    private array $colorIdentity;

    private string $power;
    private string $toughness;

    public static function createFromDatum($cardDatum): ?self
    {
        $result = new self();

        $result->name = $cardDatum['name'];
        $result->faceName = $cardDatum['faceName'] ?? '';
        $result->subtypes = $cardDatum['subtypes'] ?? [];
        $result->supertypes = $cardDatum['supertypes'] ?? [];
        $result->type = $cardDatum['type'] ?? [];
        $result->types = $cardDatum['types'] ?? [];
        $result->colors = $cardDatum['colors'];
        $result->colorIdentity = $cardDatum['colorIdentity'];
        $result->keywords = $cardDatum['keywords'] ?? [];
        $result->manaCost = $cardDatum['manaCost'] ?? '';
        $result->manaValue = (int) $cardDatum['convertedManaCost'] ?? 0;
        $result->number = $cardDatum['number'] ?? '';
        $result->power = $cardDatum['power'] ?? '';
        $result->rarity = $cardDatum['rarity'] ?? '';
        $result->toughness = $cardDatum['toughness'] ?? '';
        $result->parseTypes();

        return $result;
    }

    private function parseTypes()
    {
        if (in_array('Land', $this->types) && in_array('Basic', $this->supertypes)) {
            $this->type = CardDefinition::T_BASIC_LAND;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFaceName(): string
    {
        return $this->faceName;
    }

    public function getNumber(): string
    {
        return $this->number;
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

    public function getSuperTypes(): array
    {
        return $this->supertypes;
    }

    public function getColors(): array
    {
        return $this->colors;
    }

    public function getColorIdentity(): array
    {
        return $this->colorIdentity;
    }

    public function getManaCost(): string
    {
        return $this->manaCost;
    }

    public function getManaValue(): string
    {
        return $this->manaValue;
    }

    public function getPower(): string
    {
        return $this->power;
    }

    public function getToughness(): string
    {
        return $this->toughness;
    }

    public function getRarity(): string
    {
        return $this->rarity;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }
}
