<?php


namespace App\Factory;


use App\Conditions\ConditionInterface;

class ConditionFactory
{
    private $conditions = [];

    public function registerCondition(string $conditionClassName)
    {
        $condition = new $conditionClassName;

        if ($condition instanceof ConditionInterface) {
            $this->conditions[$condition->getName()] = $condition;
        }
    }

    public function getCondition(string $conditionName): ?ConditionInterface
    {
        return $this->conditions[$conditionName] ?? null;
    }
}
