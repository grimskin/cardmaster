<?php


namespace App\Scenarios;


use App\Conditions\ConditionInterface;
use App\Model\Library;

interface ScenarioInterface
{
    public function getScenarioName(): string;

    public function addCondition(ConditionInterface $condition);

    public function getRequiredHandSize(): int;

    public function setPassCount(int $passCount);

    public function getSuccessCount(): int;

    public function runSimulation();

    public function setLibrary(Library $library);
}
