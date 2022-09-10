<?php

namespace App\Command;

use App\Domain\TestAssistant;
use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Model\DeckDefinition;
use App\Scenarios\ScenarioConfig;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cm:batch'
)]
class BatchCommand extends Command
{
    private CardsFactory $cardsFactory;
    private ConditionFactory $conditionFactory;
    private TestAssistant $assistant;

    public function __construct(
        CardsFactory $cardsFactory,
        ConditionFactory $conditionFactory,
        TestAssistant $assistant
    ) {
        $this->cardsFactory = $cardsFactory;
        $this->conditionFactory = $conditionFactory;
        $this->assistant = $assistant;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('running batch');

        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 7);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 7);
        $deck->addCards($this->cardsFactory->getCard('stub'), 26);
        $this->assistant->addDeck('14-lands', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 7);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 8);
        $deck->addCards($this->cardsFactory->getCard('stub'), 25);
        $this->assistant->addDeck('15-lands', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 8);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 8);
        $deck->addCards($this->cardsFactory->getCard('stub'), 24);
        $this->assistant->addDeck('16-lands', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 8);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 9);
        $deck->addCards($this->cardsFactory->getCard('stub'), 23);
        $this->assistant->addDeck('17-lands', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 9);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 9);
        $deck->addCards($this->cardsFactory->getCard('stub'), 22);
        $this->assistant->addDeck('18-lands', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 9);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 10);
        $deck->addCards($this->cardsFactory->getCard('stub'), 21);
        $this->assistant->addDeck('19-lands', $deck);

        $passCount = 10000;

        $config = new ScenarioConfig();
        $config->setPassCount($passCount);

        $this->assistant->setConfig($config);

        $this->assistant->addConditionsPack('Can cast Uurg', [
            $this->conditionFactory->getCondition('can-cast', ['Nemata, Primeval Warden'], 4),
        ]);

        $result = $this->assistant->runSimulations();

        $output->writeln(
            json_encode($result, JSON_PRETTY_PRINT)
        );

        return Command::SUCCESS;
    }
}
