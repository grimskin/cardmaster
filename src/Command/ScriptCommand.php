<?php

namespace App\Command;

use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\DeckDefinition;
use App\Service\ExperimentFileReader;
use App\Service\StatsCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExperimentCommand extends Command
{
    protected static $defaultName = 'cm:experiment';

    private $cardsFactory;
    private $scenarioFactory;
    private $conditionFactory;
    private $collector;
    private $fileReader;

    public function __construct(
        CardsFactory $cardsFactory,
        ScenarioFactory $scenarioFactory,
        ConditionFactory $conditionFactory,
        StatsCollector $collector,
        ExperimentFileReader $fileReader
    ) {
        $this->cardsFactory = $cardsFactory;
        $this->scenarioFactory = $scenarioFactory;
        $this->conditionFactory = $conditionFactory;
        $this->collector = $collector;
        $this->fileReader = $fileReader;

        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        parent::configure();

        $this->addArgument('filename', InputArgument::REQUIRED, 'file name with experiment description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('welcome');

        $filename = $input->getArgument('filename');


        $gotFile = $this->fileReader->hasFile($filename);
        $output->writeln($filename . ' ' . ($gotFile ? 'exists' : 'absent'));

        $script = $this->fileReader->readFile($filename);
        $output->writeln(print_r($script, 1));
        return;


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
