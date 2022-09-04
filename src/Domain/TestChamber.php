<?php


namespace App\Domain;


use App\Conditions\CanCast;
use App\Conditions\HasCard;
use App\Conditions\WrappedCondition;
use App\Model\DeckDefinition;
use App\Model\ExperimentResult;
use App\Model\Library;
use App\Scenarios\ScenarioConfig;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class TestChamber
{
    /**
     * @var WrappedCondition[]
     */
    private array $conditions = [];

    protected array $cardsOfInterest = [];
    protected int $maxConditionTurn = 0;
    /** @var WrappedCondition[]  */
    protected array $conditionsByTurns = [];

    private LoggerInterface $logger;
    private ScenarioConfig $scenarioConfig;
    private DeckDefinition $deck;

    public function init(): void
    {
        $this->cardsOfInterest = [];
        $this->conditions = [];
        $this->conditionsByTurns = [];
        $this->maxConditionTurn = 0;
    }

    #[Required]
    public function setLogger(LoggerInterface $montyLogger): void
    {
        $this->logger = $montyLogger;
    }

    public function setScenarioConfig(ScenarioConfig $scenarioConfig): void
    {
        $this->scenarioConfig = $scenarioConfig;
    }

    public function addCondition($condition): void
    {
        $this->conditions[$condition->getName()] = new WrappedCondition($condition);
    }

    public function setDeck(DeckDefinition $deck): void
    {
        $this->deck = $deck;
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

    protected function runIteration(Library $library, ExperimentResult $result): void
    {
        $dealer = new Dealer();
        $dealer->setLogger($this->logger);

        foreach ($this->cardsOfInterest as $card) $dealer->addCardOfInterest($card);

        $dealer->debugMode();
        $hand = $dealer->getStartingHand($library);

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
            $result->tickSuccessCount();
        }

        $result->tickPassCount();
    }

    public function runSimulation(): ExperimentResult
    {
        $library = $this->deck->getLibrary();

        $experimentResult = new ExperimentResult();

        $passes = $this->scenarioConfig->getPassCount();
        $this->cardsOfInterest = [];

        foreach ($this->conditions as $condition) {
            if (in_array($condition::class, [CanCast::class, HasCard::class])) {
                $cardName = $condition->getParam();
                $this->cardsOfInterest[$cardName] = $cardName;
            }
        }

        $this->prepareConditions();

        while ($passes) {
            $this->runIteration($library, $experimentResult);

            $passes--;
        }

        foreach ($this->conditions as $condition) {
            $experimentResult->addCondition($condition);
        }

        $this->init();

        return $experimentResult;
    }
}
