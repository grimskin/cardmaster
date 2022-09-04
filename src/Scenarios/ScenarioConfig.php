<?php


namespace App\Scenarios;


class ScenarioConfig
{
    protected int $passCount = 10000;
    protected int $deckSize = 60;

    public function getPassCount(): int
    {
        return $this->passCount;
    }

    public function setPassCount(int $passCount): void
    {
        $this->passCount = $passCount;
    }

    public function getDeckSize(): int
    {
        return $this->deckSize;
    }

    public function setDeckSize(int $deckSize): void
    {
        $this->deckSize = $deckSize;
    }
}
