<?php


namespace App\Conditions;


use App\Factory\CardsFactory;
use App\Model\CardDefinition;

class WrappedCondition implements ConditionInterface
{
    private ConditionInterface $condition;
    private int $passCount = 0;
    private int $successCount = 0;
    private bool $lastResult = false;

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getPassCount(): int
    {
        return $this->passCount;
    }

    public function getLastResult(): bool
    {
        return $this->lastResult;
    }

    public function testHand(CardDefinition ...$cardDefinitions): bool
    {
        $result = $this->condition->testHand(...$cardDefinitions);

        $this->lastResult = $result;
        ++$this->passCount;
        if ($result) ++$this->successCount;

        return $result;
    }

    public function __construct(ConditionInterface $condition)
    {
        $this->condition = $condition;
    }

    public function getName(): string
    {
        return $this->condition->getName();
    }

    public function getReadableName(): string
    {
        return $this->condition->getReadableName();
    }

    public function addParams(array $params)
    {
        return $this->condition->addParams($params);
    }

    public function setTurn(int $turn)
    {
        $this->condition->setTurn($turn);
    }

    public function getParam(int $paramNumber = 0): mixed
    {
        return $this->condition->getParam($paramNumber);
    }

    public function setCardsFactory(CardsFactory $cardsFactory)
    {
        return $this->condition->setCardsFactory($cardsFactory);
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            $this->condition->jsonSerialize(),
            [
                'success' => $this->successCount,
                'total' => $this->passCount,
            ],
        );
    }
}
