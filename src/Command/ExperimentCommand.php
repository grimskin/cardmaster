<?php

namespace App\Command;

use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\DeckDefinition;
use App\Service\StatsCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'cm:test';

    private $cardsFactory;
    private $scenarioFactory;
    private $conditionFactory;
    private $collector;

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

        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('welcome');


        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 12);
        $deck->addCards($this->cardsFactory->getCard('Mountain'), 12);
        $deck->addCards($this->cardsFactory->getCard('stub'), 36);

        $passCount = 10000;

        $this->collector->addCondition($this->conditionFactory->getCondition('has-three-lands'));
        $this->collector->setDeck($deck);
        $this->collector->setPassCount($passCount);
        $this->collector->setScenario($this->scenarioFactory->getScenario('starting-hand'));
        $this->collector->runSimulation();

        $output->writeln($this->collector->getSuccessCount() . ' / ' . $passCount);
    }
}
