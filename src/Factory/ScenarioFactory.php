<?php


namespace App\Factory;


use App\Scenarios\ScenarioInterface;

class ScenarioFactory
{
    private $scenarios = [];

    public function registerScenario(string $scenarioClassName)
    {
        $scenario = new $scenarioClassName;

        if ($scenario instanceof ScenarioInterface) {
            $this->scenarios[$scenario->getScenarioName()] = $scenario;
        }
    }

    public function getScenario(string $scenarioName): ?ScenarioInterface
    {
        return $this->scenarios[$scenarioName] ?? null;
    }
}
