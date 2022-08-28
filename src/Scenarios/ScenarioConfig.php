<?php


namespace App\Scenarios;


class ScenarioConfig
{
    protected int $passCount = 10000;

    public function getPassCount(): int
    {
        return $this->passCount;
    }

    public function setPassCount(int $passCount): void
    {
        $this->passCount = $passCount;
    }
}
