<?php


namespace App\Conditions;


use App\Model\CardDefinition;

abstract class AbstractCondition implements ConditionInterface
{
    protected $params;

    abstract public function getName(): string;

    abstract public function testHand(CardDefinition ... $cardDefinitions): bool;

    public function addParams(array $params)
    {
        $this->params = $params;
    }
}
