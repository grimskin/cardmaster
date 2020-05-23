<?php


namespace App\Model;


use App\Conditions\ConditionInterface;
use JsonSerializable;

class ExperimentResult implements JsonSerializable
{
    private $passCount = 0;
    private $successCount = 0;

    private $conditionStats = [];

    public function tickPassCount()
    {
        ++$this->passCount;
    }

    public function tickSuccessCount()
    {
        ++$this->successCount;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getPassCount()
    {
        return $this->passCount;
    }

    public function __toString()
    {
        return $this->successCount . '/' . $this->passCount;
    }

    public function addCondition(ConditionInterface $condition)
    {
        $this->conditionStats[] = $condition;
    }

    public function jsonSerialize()
    {
        return [
            'success' => $this->successCount,
            'total' => $this->passCount,
            'conditions' => $this->conditionStats,
//            'conditions' => array_map(function(ConditionInterface $item) { return $item->jsonSerialize(); }, $this->conditionStats),
        ];
    }
}
