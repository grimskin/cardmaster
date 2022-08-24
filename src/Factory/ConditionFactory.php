<?php


namespace App\Factory;


use App\Conditions\ConditionInterface;
use Exception;

class ConditionFactory
{
    /**
     * @var ConditionInterface[]
     */
    private array $conditions = [];

    private CardsFactory $cardsFactory;

    public function __construct(CardsFactory $cardsFactory)
    {
        $this->cardsFactory = $cardsFactory;
    }

    public function registerCondition(string $conditionClassName): void
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
                'title' => $condition->getReadableName(),
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
        $result->setCardsFactory($this->cardsFactory);

        return $result;
    }
}
