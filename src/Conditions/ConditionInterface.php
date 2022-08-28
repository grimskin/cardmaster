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

    public function getSuccessCount(): int;

    public function getPassCount(): int;

    public function recordCheck(bool $success);

    public function getParam(int $paramNumber): mixed;

    public function setCardsFactory(CardsFactory $cardsFactory);
}