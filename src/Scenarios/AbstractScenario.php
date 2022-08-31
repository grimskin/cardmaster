<?php


namespace App\Scenarios;


use App\Conditions\CanCast;
use App\Conditions\ConditionInterface;
use App\Conditions\HasCard;
use App\Conditions\WrappedCondition;
use App\Domain\Dealer;
use App\Model\ExperimentResult;
use App\Model\Library;
use Psr\Log\LoggerInterface;

abstract class AbstractScenario implements ScenarioInterface
{
    protected bool $isDebugMode = false;
    /** @var WrappedCondition[]  */
    protected array $conditions = [];
    protected int $passCount = 0;
    protected int $successCount = 0;
    protected ScenarioConfig $config;
    protected Library $library;
    protected ?LoggerInterface $logger = null;
    protected array $cardsOfInterest = [];
    protected int $maxConditionTurn = 0;
    /** @var ConditionInterface[]  */
    protected array $conditionsByTurns = [];

    public function getReadableName(): string
    {
        return $this->getScenarioName();
    }

    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[$condition->getName()] = new WrappedCondition($condition);
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

        $this->prepareConditions();

        while ($passes) {
            $this->runIteration($this->library, $result);

            $passes--;
        }

        foreach ($this->conditions as $condition) {
            $result->addCondition($condition);
        }
    }

    protected function prepareConditions(): void
    {
        $maxTurn = 0;

        foreach ($this->conditions as $condition) {
            if ($condition->getTurn() > $maxTurn) $maxTurn = $condition->getTurn();

            if (!isset($this->conditionsByTurns[$condition->getTurn()]))
                $this->conditionsByTurns[$condition->getTurn()] = [];

            $this->conditionsByTurns[$condition->getTurn()][] = $condition;
        }

        for ($i=1; $i<=$maxTurn; $i++) {
            if (!isset($this->conditionsByTurns[$i])) $this->conditionsByTurns[$i] = [];
        }

        $this->maxConditionTurn = $maxTurn;
    }

    protected function runIteration(Library $library, ExperimentResult $result)
    {
        $dealer = new Dealer();
        if ($this->logger) $dealer->setLogger($this->logger);

        foreach ($this->cardsOfInterest as $card) $dealer->addCardOfInterest($card);

        $dealer->debugMode();
        $hand = $dealer->getStartingHand($library, $this->getRequiredHandSize());

        for ($i=1; $i<=$this->maxConditionTurn; $i++) {
            foreach ($this->conditionsByTurns[$i] as $condition) {
                $condition->testHand(...$hand);
            }

            $hand[] = $library->draw();
        }

        $success = true;
        foreach ($this->conditions as $condition) {
            if ($condition->getLastResult()) continue;

            $success = false;
            break;
        }

        if ($success) {
            $this->successCount++;
            $result->tickSuccessCount();
        }

        $result->tickPassCount();
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
