<?php


namespace App\Conditions;


use App\Model\CardDefinition;

abstract class AbstractCondition implements ConditionInterface
{
    protected $params;

    protected $passCount = 0;

    protected $successCount = 0;

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
}
