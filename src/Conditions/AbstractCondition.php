<?php


namespace App\Conditions;


use App\Factory\CardsFactory;
use App\Model\CardDefinition;
use ReturnTypeWillChange;

abstract class AbstractCondition implements ConditionInterface
{
    protected array $params = [];

    protected int $passCount = 0;

    protected int $successCount = 0;

    protected int $turn = 0;

    protected CardsFactory $cardsFactory;

    abstract public function getName(): string;

    abstract public function testHand(CardDefinition ... $cardDefinitions): bool;

    public function getReadableName(): string
    {
        return $this->getName();
    }

    public function addParams(array $params)
    {
        $this->params = $params;
    }

    public function setTurn(int $turn)
    {
        $this->turn = $turn;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getPassCount(): int
    {
        return $this->passCount;
    }

    public function recordCheck(bool $success)
    {
        ++$this->passCount;

        if ($success) {
            ++$this->successCount;
        }
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'params' => $this->params,
            'success' => $this->successCount,
            'total' => $this->passCount,
        ];
    }

    public function setCardsFactory(CardsFactory $cardsFactory) {
        $this->cardsFactory = $cardsFactory;
    }
}
