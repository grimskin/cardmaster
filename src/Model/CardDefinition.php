<?php

namespace App\Model;

use App\Domain\ManaCost;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class CardDefinition implements JsonSerializable
{
    private ?CardData $cardData;

    const T_BASIC_LAND = 'T_BASIC_LAND';
    const T_LAND = 'T_LAND';
    const T_ENCHANTMENT = 'Enchantment';
    const T_ARTIFACT = 'Artifact';
    const T_CREATURE = 'Creature';
    const T_INSTANT = 'Instant';
    const T_SORCERY = 'Sorcery';
    const T_PLANESWALKER = 'Planeswalker';

    const ST_LEGENDARY = 'Legendary';

    const COLOR_WHITE = 'W';
    const COLOR_BLUE = 'U';
    const COLOR_BLACK = 'B';
    const COLOR_RED = 'R';
    const COLOR_GREEN = 'G';

    private string $name = '';
    private string $faceName = '';
    private bool $isStub = false;
    private array $colorIdentity = [];

    private string $type = '';
    private array $types = [];
    private array $subtypes = [];
    private array $superTypes = [];

    private ?ManaCost $manaCost;

    public function isStub(): bool
    {
        return $this->isStub;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFaceName(): string
    {
        return $this->faceName;
    }

    public function getCanonizedName(): string
    {
        return strtolower($this->name);
    }

    public function getManaCost(): ?ManaCost
    {
        return $this->manaCost;
    }

    #[Pure]
    public static function define(string $name,bool $isStub = false): self
    {
        $result = new self();

        $result->name = $name;
        $result->isStub = $isStub;

        return $result;
    }

    public function isLand(): bool
    {
        return $this->type == self::T_BASIC_LAND || $this->type == self::T_LAND || in_array('Land', $this->types);
    }

    public function isBasicLand(): bool
    {
        return $this->type == self::T_BASIC_LAND;
    }

    public function isCreature(): bool
    {
        return in_array(self::T_CREATURE, $this->types);
    }

    public function isLegendary(): bool
    {
        return in_array(self::ST_LEGENDARY, $this->superTypes);
    }

    #[Pure]
    public function getCreatureTypes(): array
    {
        if (!$this->isCreature()) return [];

        return $this->subtypes;
    }

    public function getSubTypes(): array
    {
        return $this->subtypes;
    }

    #[Pure]
    public function canProduce(string $color): bool
    {
        if ($this->isLand() && in_array($color, $this->colorIdentity)) {
            return true;
        }

        return false;
    }

    public function isOfType(string $type): bool
    {
        return in_array($type, $this->types);
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function absorbData(CardData $cardData)
    {
        $this->cardData = $cardData;

        if ($this->isStub()) {
            return ;
        }

        $this->name = $cardData->getName();
        $this->faceName = $cardData->getFaceName();
        $this->type = $cardData->getType();
        $this->types = $cardData->getTypes();
        $this->subtypes = $cardData->getSubtypes();
        $this->superTypes = $cardData->getSuperTypes();
        $this->colorIdentity = $cardData->getColorIdentity();
        $this->manaCost = new ManaCost($cardData->getManaCost());
    }

    #[Pure]
    public function getNumber(): string
    {
        return $this->cardData?->getNumber();
    }

    #[ArrayShape([
        'name' => "string",
        'isLand' => "string",
        'manaCost' => "App\\Domain\\ManaCost",
        'canProduce' => "string"
    ])]
    public function jsonSerialize(): array
    {
        $result = [
            'name' => $this->getName(),
            'isLand' => $this->isLand() ? 'true' : 'false',
            'manaCost' => $this->manaCost,
        ];

        $result['canProduce'] = implode(', ', array_filter([
            $this->canProduce(self::COLOR_WHITE) ? self::COLOR_WHITE : null,
            $this->canProduce(self::COLOR_BLUE) ? self::COLOR_BLUE : null,
            $this->canProduce(self::COLOR_BLACK) ? self::COLOR_BLACK : null,
            $this->canProduce(self::COLOR_RED) ? self::COLOR_RED : null,
            $this->canProduce(self::COLOR_GREEN) ? self::COLOR_GREEN : null,
        ]));

        return $result;
    }
}
