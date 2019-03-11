<?php


namespace App\Scenarios;


use App\Conditions\ConditionInterface;
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

    public function runSimulation()
    {
        $passes = $this->passCount;

        while ($passes) {
            $success = true;

            foreach ($this->conditions as $condition) {
                $this->library->reset();
                $this->library->shuffle($this->getRequiredHandSize());

                $hand = $this->library->drawHand($this->getRequiredHandSize());

                if ($condition->testHand(...$hand)) {
                    continue;
                }

                $success = false;
                break;
            }

            if ($success) {
                $this->successCount++;
            }

            $passes--;
        }
    }
}
