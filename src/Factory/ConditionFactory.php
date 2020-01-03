<?php


namespace App\Factory;


use App\Conditions\ConditionInterface;
use Exception;

class ConditionFactory
{
    /**
     * @var ConditionInterface[]
     */
    private $conditions = [];

    public function registerCondition(string $conditionClassName)
    {
        $condition = new $conditionClassName;

        if ($condition instanceof ConditionInterface) {
            $this->conditions[$condition->getName()] = $condition;
        }
    }

    public function getRegisteredConditions(): array
    {
        return array_map(function (ConditionInterface $condition) {
            return [
                'name' => $condition->getName(),
                'description' => $condition->getDescription(),
            ];
        }, $this->conditions);
    }

    public function getCondition(string $conditionName, array $params): ?ConditionInterface
    {
        $result = $this->conditions[$conditionName] ?? null;

        if (!$result) {
            throw new Exception('No condition named ' . $conditionName . ' found');
        }

        $result->addParams($params);

        return $result;
    }
}
