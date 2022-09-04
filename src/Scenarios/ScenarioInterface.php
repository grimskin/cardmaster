<?php


namespace App\Scenarios;


use App\Conditions\ConditionInterface;
use App\Model\ExperimentResult;
use App\Model\Library;
use Psr\Log\LoggerInterface;

/**
 * @deprecated
 */
interface ScenarioInterface
{
    public function getScenarioName(): string;

    public function getReadableName(): string;

    public function addCondition(ConditionInterface $condition);

    public function getRequiredHandSize(): int;

    public function setPassCount(int $passCount);

    public function getSuccessCount(): int;

    public function setConfig(ScenarioConfig $config);

    public function runSimulation(ExperimentResult $result);

    public function setLibrary(Library $library);

    public function setDebugMode(bool $debugMode);

    public function setLogger(LoggerInterface $logger): void;
}
