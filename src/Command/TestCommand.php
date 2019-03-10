<?php

namespace App\Command;

use App\Factory\CardsFactory;
use App\Model\DeckDefinition;
use App\Model\Library;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'cm:test';

    private $cardsFactory;

    public function __construct(CardsFactory $cardsFactory)
    {
        $this->cardsFactory = $cardsFactory;

        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('welcome');


        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('forest'), 5);
        $deck->addCards($this->cardsFactory->getCard('mountain'), 5);

        $library = Library::make($deck);

        $library->shuffle(7);

        while ($card = $library->draw()) {
            $output->write($card->getName());
            $output->write(' ');
        }
        $output->writeln('');
    }
}
