<?php


namespace App\Service;


use App\Conditions\HasThreeLands;
use App\Model\Library;
use App\Scenarios\ScenarioInterface;

class StatsCollector
{
    private $conditions = [];
    /**
     * @var Library
     */
    private $library;
    private $passCount = 0;
    /**
     * @var ScenarioInterface
     */
    private $scenario;

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

    public function setScenario(ScenarioInterface $scenario) {
        $this->scenario = $scenario;
    }

    public function addCondition($condition)
    {
        $this->conditions[] = $condition;
    }

    public function runSimulation()
    {
        $this->scenario->setPassCount($this->passCount);
        foreach ($this->conditions as $condition) {
            $this->scenario->addCondition($condition);
        }
        $this->scenario->setLibrary($this->library);
        $this->scenario->runSimulation();
        $this->successCount = $this->scenario->getSuccessCount();
    }
}
