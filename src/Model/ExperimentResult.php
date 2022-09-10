<?php


namespace App\Model;


use App\Conditions\WrappedCondition;
use JsonSerializable;
use ReturnTypeWillChange;

class ExperimentResult implements JsonSerializable
{
    private int $passCount = 0;
    private int $successCount = 0;

    private array $conditionStats = [];

    public function tickPassCount()
    {
        ++$this->passCount;
    }

    public function tickSuccessCount()
    {
        ++$this->successCount;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getPassCount(): int
    {
        return $this->passCount;
    }

    public function __toString()
    {
        return $this->successCount . '/' . $this->passCount;
    }

    public function addCondition(WrappedCondition $condition)
    {
        $this->conditionStats[] = $condition;
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'success' => $this->successCount,
            'total' => $this->passCount,
            'conditions' => $this->conditionStats,
//            'conditions' => array_map(function(ConditionInterface $item) { return $item->jsonSerialize(); }, $this->conditionStats),
        ];
    }
}
