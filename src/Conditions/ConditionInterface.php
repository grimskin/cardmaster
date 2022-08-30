<?php


namespace App\Conditions;


use App\Factory\CardsFactory;
use App\Model\CardDefinition;
use JsonSerializable;

interface ConditionInterface extends JsonSerializable
{
    public function getName(): string;

    public function getReadableName(): string;

    public function testHand(CardDefinition ... $cardDefinitions): bool;

    public function addParams(array $params);

    public function setTurn(int $turn);

    public function getParam(int $paramNumber = 0): mixed;

    public function setCardsFactory(CardsFactory $cardsFactory);
}
