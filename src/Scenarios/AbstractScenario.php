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
    protected array $conditions = [];
    protected int $passCount = 0;
    protected int $successCount = 0;
    protected ScenarioConfig $config;

    protected Library $library;

    public function getReadableName(): string
    {
        return $this->getScenarioName();
    }

    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[$condition->getName()] = $condition;
    }

    /**
     * @deprecated use ScenarioConfig
     */
    public function setPassCount(int $passCount)
    {
        $this->passCount = $passCount;
    }

    public function setConfig(ScenarioConfig $config)
    {
        $this->config = $config;
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
        $passes = $this->config->getPassCount();

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
