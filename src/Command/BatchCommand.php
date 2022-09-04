<?php

namespace App\Command;

use App\Domain\TestAssistant;
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
        $deck->addCards($this->cardsFactory->getCard('Forest'), 10);
        $deck->addCards($this->cardsFactory->getCard('Mountain'), 10);
        $deck->addCards($this->cardsFactory->getCard('Rockfall Vale'), 4);
        $deck->addCards($this->cardsFactory->getCard('stub'), 36);

        $passCount = 10000;

        $config = new ScenarioConfig();
        $config->setPassCount($passCount);

        $this->assistant->setConfig($config);

        $this->assistant->addConditionsPack('Can cast Meria', [
            $this->conditionFactory->getCondition('at-least-x-lands', [3], 3),
            $this->conditionFactory->getCondition('can-cast', ['Meria, Scholar of Antiquity'], 3),
        ]);

        $this->assistant->addDeck('24 Lands Meria', $deck);
        $result = $this->assistant->runSimulations();

            $output->writeln(
            $result['Can cast Meria']['24 Lands Meria']->getSuccessCount() . ' / ' . $passCount
        );

        return Command::SUCCESS;
    }
}
