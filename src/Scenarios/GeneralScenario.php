<?php


namespace App\Scenarios;


class GeneralScenario extends AbstractScenario
{
    public function getScenarioName(): string
    {
        return 'general-scenario';
    }

    public function getRequiredHandSize(): int
    {
        return 7;
    }
}
