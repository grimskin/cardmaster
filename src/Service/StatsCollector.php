<?php


namespace App\Service;


use App\Conditions\HasThreeLands;
use App\Model\Library;

class StatsCollector
{
    private $conditions = [];
    /**
     * @var Library
     */
    private $library;
    private $passCount = 0;

    private $successCount = 0;

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function setPassCount(int $passCount)
    {
        $this->passCount = $passCount;
    }

    public function setLibrary(Library $library)
    {
        $this->library = $library;
    }

    public function addCondition($condition)
    {
        $this->conditions[] = $condition;
    }

    public function runSimulation()
    {
        $passes = $this->passCount;
        /** @var HasThreeLands $condition */
        $condition = $this->conditions[0];

        while ($passes) {
            $this->library->reset();
            $this->library->shuffle(7);
            $hand = $this->library->drawHand(7);

            if ($condition->testHand(...$hand)) {
                $this->successCount++;
            }

            $passes--;
        }
    }
}
