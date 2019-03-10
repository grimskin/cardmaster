<?php

namespace App\Command;

use App\Conditions\HasThreeLands;
use App\Factory\CardsFactory;
use App\Model\DeckDefinition;
use App\Model\Library;
use App\Service\StatsCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'cm:test';

    private $cardsFactory;
    private $collector;

    public function __construct(CardsFactory $cardsFactory, StatsCollector $collector)
    {
        $this->cardsFactory = $cardsFactory;
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

        $library = Library::make($deck);

        $passCount = 10000;

        $this->collector->addCondition(new HasThreeLands());
        $this->collector->setLibrary($library);
        $this->collector->setPassCount($passCount);
        $this->collector->runSimulation();

        $output->writeln($this->collector->getSuccessCount() . ' / ' . $passCount);
    }
}
