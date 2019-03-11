<?php


namespace App\Service;


use App\Model\DeckDefinition;
use App\Scenarios\ScenarioInterface;

class StatsCollector
{
    private $conditions = [];
    /**
     * @var DeckDefinition
     */
    private $deck;
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

    public function setDeck(DeckDefinition $deck)
    {
        $this->deck = $deck;
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
        $this->scenario->setLibrary($this->deck->getLibrary());
        $this->scenario->runSimulation();
        $this->successCount = $this->scenario->getSuccessCount();
    }
}
