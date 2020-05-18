<?php

namespace App\Model;

use JsonSerializable;

class CardDefinition implements JsonSerializable
{
    const T_BASIC_LAND = 'T_BASIC_LAND';
    const T_LAND = 'T_LAND';

    private $name = '';
    private $isStub = false;
    private $colorIdentity = [];

    private $type = '';
    private $types = [];

    public function isStub(): bool
    {
        return $this->isStub;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCanonizedName(): string
    {
        return strtolower($this->name);
    }

    public static function define(
        string $name,
        bool $isStub = false
    ): self {
        $result = new self();

        $result->name = $name;
        $result->isStub = $isStub;

        return $result;
    }

    public function canProduceWhite(): bool
    {
        return $this->canProduce('W');
    }

    public function canProduceBlue(): bool
    {
        return $this->canProduce('U');
    }

    public function canProduceBlack(): bool
    {
        return $this->canProduce('B');
    }

    public function canProduceRed(): bool
    {
        return $this->canProduce('R');
    }

    public function canProduceGreen(): bool
    {
        return $this->canProduce('G');
    }

    public function isLand(): bool
    {
        return $this->type == self::T_BASIC_LAND || $this->type == self::T_LAND || in_array('Land', $this->types);
    }

    private function canProduce(string $color)
    {
        if ($this->isLand() && in_array($color, $this->colorIdentity)) {
            return true;
        }

        return false;
    }

    public function getData(CardData $cardData)
    {
        if ($this->isStub()) {
            return ;
        }

        $this->name = $cardData->getName();
        $this->type = $cardData->getType();
        $this->types = $cardData->getTypes();
        $this->colorIdentity = $cardData->getColorIdentity();
    }

    public function jsonSerialize()
    {
        $result = [
            'name' => $this->getName(),
            'isLand' => $this->isLand() ? 'true' : 'false',
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
