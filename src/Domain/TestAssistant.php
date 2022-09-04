<?php


namespace App\Domain;


use App\Model\DeckDefinition;
use App\Scenarios\ScenarioConfig;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class TestAssistant
{
    private ScenarioConfig $config;
    private array $conditionPacks = [];
    /** @var DeckDefinition[] */
    private array $decks = [];
    private array $counterPacks = [];

    private TestChamber $chamber;
    private LoggerInterface $logger;

    public function __construct(TestChamber $chamber)
    {
        $this->chamber = $chamber;
    }

    public function addConditionsPack(string $packName, array $conditions): void
    {
        $this->conditionPacks[$packName] = $conditions;
    }

    public function addDeck(string $deckName, DeckDefinition $deck): void
    {
        $this->decks[$deckName] = $deck;
    }

    public function addCountersPack(string $packName, array $counters)
    {
        $this->counterPacks[$packName] = $counters;
    }

    public function setConfig(ScenarioConfig $config)
    {
        $this->config = $config;
    }

    #[Required]
    public function setLogger(LoggerInterface $assistantLogger): void
    {
        $this->logger = $assistantLogger;
    }

    public function runSimulations(): array
    {
        $results = [];

        foreach ($this->decks as $deckName => $deck) {
            foreach ($this->conditionPacks as $packName => $conditions) {
                $this->chamber->setScenarioConfig($this->config);
                $this->chamber->setDeck($deck);
                $this->chamber->addConditions($conditions);

                $this->logger->info('########### Test session:');
                $this->logger->info('Deck:       '.$deckName);
                $this->logger->info('Conditions: '.$packName);

                $sessionResult = $this->chamber->runSimulation();
                $this->logger->info('Result:     '.$sessionResult->getSuccessCount().'/'.$sessionResult->getPassCount());

                $results[$packName][$deckName] = $sessionResult;
            }
        }

        return $results;
    }
}
