<?php


namespace App\Scenarios;


use App\Conditions\ConditionInterface;
use App\Model\ExperimentResult;
use App\Model\Library;

abstract class AbstractScenario implements ScenarioInterface
{
    /**
     * @var ConditionInterface[]
     */
    protected $conditions = [];
    protected $passCount = 0;
    protected $successCount = 0;
    /**
     * @var Library
     */
    protected $library;

    public function getReadableName(): string
    {
        return $this->getScenarioName();
    }

    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[$condition->getName()] = $condition;
    }

    public function setPassCount(int $passCount)
    {
        $this->passCount = $passCount;
    }

    public function setLibrary(Library $library)
    {
        $this->library = $library;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function runSimulation(ExperimentResult $result)
    {
        $passes = $this->passCount;

        while ($passes) {
            $success = true;

            $this->library->reset();
            $this->library->shuffle();

            $hand = $this->library->drawHand($this->getRequiredHandSize());

            foreach ($this->conditions as $condition) {
                if ($condition->testHand(...$hand)) {
                    $condition->recordCheck(true);
                    continue;
                }

                $condition->recordCheck(false);
                $success = false;
            }

            if ($success) {
                $this->successCount++;
                $result->tickSuccessCount();
            }

            $result->tickPassCount();

            $passes--;
        }

        foreach ($this->conditions as $condition) {
            $result->addCondition($condition);
        }
    }
}
