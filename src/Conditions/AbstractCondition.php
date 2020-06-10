<?php


namespace App\Conditions;


use App\Factory\CardsFactory;
use App\Model\CardDefinition;

abstract class AbstractCondition implements ConditionInterface
{
    protected $params;

    protected $passCount = 0;

    protected $successCount = 0;

    /**
     * @var CardsFactory
     */
    protected $cardsFactory;

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

    public function jsonSerialize()
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
