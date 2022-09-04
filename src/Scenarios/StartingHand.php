<?php


namespace App\Scenarios;

/**
 * @deprecated
 */
class StartingHand extends AbstractScenario
{
    public function getScenarioName(): string
    {
        return 'starting-hand';
    }

    public function getRequiredHandSize(): int
    {
        return 7;
    }
}
