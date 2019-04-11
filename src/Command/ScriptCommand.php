<?php

namespace App\Command;

use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\DeckDefinition;
use App\Service\ScriptFileReader;
use App\Service\ScriptRunner;
use App\Service\StatsCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScriptCommand extends Command
{
    protected static $defaultName = 'cm:script';

    private $runner;
    private $cardsFactory;
    private $scenarioFactory;
    private $conditionFactory;
    private $collector;
    private $fileReader;

    public function __construct(
        ScriptRunner $runner,
        CardsFactory $cardsFactory,
        ScenarioFactory $scenarioFactory,
        ConditionFactory $conditionFactory,
        StatsCollector $collector,
        ScriptFileReader $fileReader
    ) {
        $this->runner = $runner;
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

        $result = $this->runner->runScript($script);

        $output->writeln($result);
    }
}
