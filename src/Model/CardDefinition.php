<?php

namespace App\Model;

class CardDefinition
{
    const T_BASIC_LAND = 'T_BASIC_LAND';
    const T_LAND = 'T_LAND';

    private $name = '';
    private $isStub = false;
    private $colorIdentity = [];

    private $type = '';

//    private $produceWhite = false;
//    private $produceBlue = false;
//    private $produceBlack = false;
//    private $produceRed = false;
//    private $produceGreen = false;

    public function isStub(): bool
    {
        return $this->isStub;
    }

    public function getName(): string
    {
        return $this->name;
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
        return $this->type == self::T_BASIC_LAND || $this->type == self::T_LAND;
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
        $this->colorIdentity = $cardData->getColorIdentity();

//        switch ($cardData->getName()) {
//            case 'Plains':
//                $this->produceWhite = true;
//                break;
//            case 'Island':
//                $this->produceBlue = true;
//                break;
//            case 'Swamp':
//                $this->produceBlack = true;
//                break;
//            case 'Mountain':
//                $this->produceRed = true;
//                break;
//            case 'Forest':
//                $this->produceGreen = true;
//                break;
//        }
    }
}
