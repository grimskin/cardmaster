<?php


namespace App\Conditions;


use App\Factory\CardsFactory;
use App\Model\CardDefinition;
use Exception;
use ReturnTypeWillChange;

abstract class AbstractCondition implements ConditionInterface
{
    protected array $params = [];

    protected int $turn = 1;

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

    public function getParam(int $paramNumber = 0): mixed
    {
        return $this->params[$paramNumber];
    }

    public function setTurn(int $turn)
    {
        $this->turn = $turn;
    }

    public function getTurn(): int
    {
        return $this->turn;
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'params' => $this->params,
            'turn' => $this->turn,
        ];
    }

    public function setCardsFactory(CardsFactory $cardsFactory) {
        $this->cardsFactory = $cardsFactory;
    }

    protected function hasIntParamOrThrow($paramNumber = 0)
    {
        if (!isset($this->params[$paramNumber])) {
            throw new Exception('Not enough params provided to '.$this->getName().' condition');
        }

        if ($this->params[$paramNumber] != (int) $this->params[$paramNumber]) {
            throw new Exception('Param '.$paramNumber.' provided to '.$this->getName().' must be integer-ish');
        }
    }

    protected function getIntParam($paramNumber = 0): int
    {
        return (int) $this->params[$paramNumber];
    }
}
