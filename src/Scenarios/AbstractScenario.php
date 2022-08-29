<?php


namespace App\Scenarios;


use App\Conditions\AtLeastXLands;
use App\Conditions\AtMostXLands;
use App\Conditions\CanCast;
use App\Conditions\ConditionInterface;
use App\Conditions\HasCard;
use App\Domain\Dealer;
use App\Model\CardDefinition;
use App\Model\ExperimentResult;
use App\Model\Library;
use Exception;

abstract class AbstractScenario implements ScenarioInterface
{
    protected bool $isDebugMode = false;
    /**
     * @var ConditionInterface[]
     */
    protected array $conditions = [];
    protected int $passCount = 0;
    protected int $successCount = 0;
    protected ScenarioConfig $config;

    protected Library $library;

    protected array $cardsOfInterest = [];

    public function getReadableName(): string
    {
        return $this->getScenarioName();
    }

    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[$condition->getName()] = $condition;
    }

    /**
     * @deprecated use ScenarioConfig
     */
    public function setPassCount(int $passCount)
    {
        $this->passCount = $passCount;
    }

    public function setConfig(ScenarioConfig $config)
    {
        $this->config = $config;
    }

    public function setLibrary(Library $library)
    {
        $this->library = $library;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function setDebugMode(bool $debugMode = true)
    {
        $this->isDebugMode = $debugMode;
    }

    public function runSimulation(ExperimentResult $result)
    {
        $passes = $this->config->getPassCount();
        $this->cardsOfInterest = [];

        foreach ($this->conditions as $condition) {
            if (in_array($condition::class, [CanCast::class, HasCard::class])) {
                $cardName = $condition->getParam();
                $this->cardsOfInterest[$cardName] = $cardName;
            }
        }

        while ($passes) {
            $this->runIteration($this->library, $result);

            $passes--;
        }

        foreach ($this->conditions as $condition) {
            $result->addCondition($condition);
        }
    }

    protected function runIteration(Library $library, ExperimentResult $result)
    {
        $success = true;

        $dealer = new Dealer();
        foreach ($this->cardsOfInterest as $card) $dealer->addCardOfInterest($card);

        $dealer->debugMode();

        $hand = $dealer->getStartingHand($library, $this->getRequiredHandSize());

        foreach ($this->conditions as $condition) {
            if ($condition->testHand(...$hand)) {
                $condition->recordCheck(true);
                continue;
            }

            $condition->recordCheck(false);
            $success = false;
        }

        if ($success) {
            $this->successCount++;
            $result->tickSuccessCount();
        }

        $result->tickPassCount();
    }
}
