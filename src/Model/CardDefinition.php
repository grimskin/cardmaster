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

    private string $name = '';
    private string $faceName = '';
    private bool $isStub = false;
    private array $colorIdentity = [];

    private string $type = '';
    private array $types = [];
    private array $subtypes = [];

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

    #[Pure]
    public function canProduceWhite(): bool
    {
        return $this->canProduce('W');
    }

    #[Pure]
    public function canProduceBlue(): bool
    {
        return $this->canProduce('U');
    }

    #[Pure]
    public function canProduceBlack(): bool
    {
        return $this->canProduce('B');
    }

    #[Pure]
    public function canProduceRed(): bool
    {
        return $this->canProduce('R');
    }

    #[Pure]
    public function canProduceGreen(): bool
    {
        return $this->canProduce('G');
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
        return in_array('Creature', $this->types);
    }

    #[Pure]
    public function getCreatureTypes(): array
    {
        if (!$this->isCreature()) return [];

        return $this->subtypes;
    }

    #[Pure]
    private function canProduce(string $color): bool
    {
        if ($this->isLand() && in_array($color, $this->colorIdentity)) {
            return true;
        }

        return false;
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
        'manaCost' => "",
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
            $this->canProduceWhite() ? 'W' : null,
            $this->canProduceBlue() ? 'U' : null,
            $this->canProduceBlack() ? 'B' : null,
            $this->canProduceRed() ? 'R' : null,
            $this->canProduceGreen() ? 'G' : null,
        ]));

        return $result;
    }
}
