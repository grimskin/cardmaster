<?php


namespace App\Service;


use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;

class SimulationSetUpHelper
{
    private $scenarioFactory;
    private $conditionFactory;
    private $cardsFactory;

    public function __construct(
        ScenarioFactory $scenarioFactory,
        ConditionFactory $conditionFactory,
        CardsFactory $cardsFactory
    ) {
        $this->scenarioFactory = $scenarioFactory;
        $this->conditionFactory = $conditionFactory;
        $this->cardsFactory = $cardsFactory;
    }

    public function configureScenario(StatsCollector $collector, string $scenarioName)
    {
        $collector->setScenario($this->scenarioFactory->getScenario($scenarioName));
    }

    public function configureCondition(StatsCollector $collector, string $conditionName, string $conditionParam)
    {
        $collector->addCondition(
            $this->conditionFactory->getCondition($conditionName, [$conditionParam])
        );
    }
}
