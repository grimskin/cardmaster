<?php

namespace App\Command;

use App\Domain\TestChamber;
use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Model\DeckDefinition;
use App\Scenarios\ScenarioConfig;
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
    private ConditionFactory $conditionFactory;
    private TestChamber $chamber;

    public function __construct(
        CardsFactory $cardsFactory,
        ConditionFactory $conditionFactory,
        TestChamber $chamber
    ) {
        $this->cardsFactory = $cardsFactory;
        $this->conditionFactory = $conditionFactory;
        $this->chamber = $chamber;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO - "Kazandu Mammoth // Kazandu Valley" is not counted as land

        $output->writeln('welcome');

        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 10);
        $deck->addCards($this->cardsFactory->getCard('Mountain'), 10);
        $deck->addCards($this->cardsFactory->getCard('Rockfall Vale'), 4);
        $deck->addCards($this->cardsFactory->getCard('Meria, Scholar of Antiquity'), 1);
        $deck->addCards($this->cardsFactory->getCard('stub'), 35);

        $passCount = 10000;

        $config = new ScenarioConfig();
        $config->setPassCount($passCount);

        $this->chamber->addCondition($this->conditionFactory->getCondition(
            'at-least-x-lands', [3], 3
        ));
        $this->chamber->addCondition($this->conditionFactory->getCondition(
            'can-cast', ['Meria, Scholar of Antiquity'], 3
        ));

        $this->chamber->setDeck($deck);
        $this->chamber->setScenarioConfig($config);
        $result = $this->chamber->runSimulation();

        $output->writeln($result->getSuccessCount() . ' / ' . $passCount);

        return Command::SUCCESS;
    }
}
