<?php


namespace App\Conditions;


use App\Model\CardDefinition;

interface ConditionInterface
{
    public function getName(): string;

    public function getDescription(): string;

    public function testHand(CardDefinition ... $cardDefinitions): bool;

    public function addParams(array $params);
}