<?php

namespace App\Command;

use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\DeckDefinition;
use App\Scenarios\ScenarioConfig;
use App\Service\StatsCollector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cm:test'
)]
class TestCommand extends Command
{
    private CardsFactory $cardsFactory;
    private ScenarioFactory $scenarioFactory;
    private ConditionFactory $conditionFactory;
    private StatsCollector $collector;

    public function __construct(
        CardsFactory $cardsFactory,
        ScenarioFactory $scenarioFactory,
        ConditionFactory $conditionFactory,
        StatsCollector $collector
    ) {
        $this->cardsFactory = $cardsFactory;
        $this->scenarioFactory = $scenarioFactory;
        $this->conditionFactory = $conditionFactory;
        $this->collector = $collector;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('welcome');


        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 12);
        $deck->addCards($this->cardsFactory->getCard('Mountain'), 12);
        $deck->addCards($this->cardsFactory->getCard('stub'), 36);

        $passCount = 10000;

        $config = new ScenarioConfig();
        $config->setPassCount($passCount);

        $this->collector->addCondition($this->conditionFactory->getCondition('at-least-x-lands', [3]));
        $this->collector->setDeck($deck);
        $this->collector->setScenario($this->scenarioFactory->getScenario('starting-hand'));
        $this->collector->setScenarioConfig($config);
        $this->collector->runSimulation();

        $output->writeln($this->collector->getSuccessCount() . ' / ' . $passCount);

        return Command::SUCCESS;
    }
}
