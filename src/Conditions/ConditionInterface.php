<?php


namespace App\Conditions;


use App\Model\CardDefinition;

interface ConditionInterface
{
    public function getName(): string;

    public function testHand(CardDefinition ... $cardDefinitions): bool;
}