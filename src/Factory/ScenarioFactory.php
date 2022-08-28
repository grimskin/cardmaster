<?php


namespace App\Factory;


use App\Scenarios\ScenarioInterface;

class ScenarioFactory
{
    private array $scenarios = [];

    public function registerScenario(string $scenarioClassName): void
    {
        $scenario = new $scenarioClassName;

        if ($scenario instanceof ScenarioInterface) {
            $this->scenarios[$scenario->getScenarioName()] = $scenario;
        }
    }

    public function getRegisteredScenarios(): array
    {
        return array_map(function (ScenarioInterface $scenario) {
            return [
                'name' => $scenario->getScenarioName(),
                'title' => $scenario->getReadableName(),
            ];
        }, $this->scenarios);
    }


    public function getScenario(string $scenarioName): ?ScenarioInterface
    {
        return $this->scenarios[$scenarioName] ?? null;
    }
}
