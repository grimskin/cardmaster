<?php


namespace App\Service;


use App\Conditions\ConditionInterface;
use App\Model\DeckDefinition;
use App\Model\ExperimentResult;
use App\Scenarios\ScenarioConfig;
use App\Scenarios\ScenarioInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @deprecated
 */
class StatsCollector
{
    /**
     * @var ConditionInterface[]
     */
    private array $conditions = [];

    private DeckDefinition $deck;
    private int $passCount = 0;

    private ScenarioInterface $scenario;

    private int $successCount = 0;

    private ?ScenarioConfig $scenarioConfig = null;

    private ?LoggerInterface $logger = null;

    public function setScenarioConfig(ScenarioConfig $scenarioConfig): void
    {
        $this->scenarioConfig = $scenarioConfig;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function setPassCount(int $passCount): void
    {
        $this->passCount = $passCount;
    }

    public function setDeck(DeckDefinition $deck): void
    {
        $this->deck = $deck;
    }

    public function setScenario(ScenarioInterface $scenario): void
    {
        $this->scenario = $scenario;
    }

    public function addCondition($condition): void
    {
        $this->conditions[] = $condition;
    }

    #[Required]
    public function setLogger(LoggerInterface $montyLogger): void
    {
        $this->logger = $montyLogger;
    }

    public function runSimulation(): ExperimentResult
    {
        if ($this->logger) $this->scenario->setLogger($this->logger);

        $this->scenario->setConfig($this->scenarioConfig ?: new ScenarioConfig());
        $this->scenario->setPassCount($this->passCount);
        foreach ($this->conditions as $condition) {
            $this->scenario->addCondition($condition);
        }
        $this->scenario->setLibrary($this->deck->getLibrary());
        $experimentResult = new ExperimentResult();
        $this->scenario->runSimulation($experimentResult);
        $this->successCount = $this->scenario->getSuccessCount();

        return $experimentResult;
    }
}
