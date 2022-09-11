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
        $deck->addCards($this->cardsFactory->getCard('Forest'), 14);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('14', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 15);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('15', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 16);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('16', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 17);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('17', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 18);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('18', $deck);


        $passCount = 1000000;

        $config = new ScenarioConfig();
        $config->setPassCount($passCount);

        $this->assistant->setConfig($config);

        $this->assistant->addConditionsPack('1', [
            $this->conditionFactory->getCondition('at-least-x-lands', [1], 2),
        ]);
        $this->assistant->addConditionsPack('2', [
            $this->conditionFactory->getCondition('at-least-x-lands', [2], 3),
        ]);
        $this->assistant->addConditionsPack('3', [
            $this->conditionFactory->getCondition('at-least-x-lands', [3], 4),
        ]);
        $this->assistant->addConditionsPack('4', [
            $this->conditionFactory->getCondition('at-least-x-lands', [4], 5),
        ]);
        $this->assistant->addConditionsPack('5', [
            $this->conditionFactory->getCondition('at-least-x-lands', [5], 6),
        ]);
        $this->assistant->addConditionsPack('6', [
            $this->conditionFactory->getCondition('at-least-x-lands', [6], 7),
        ]);
        $this->assistant->addConditionsPack('7', [
            $this->conditionFactory->getCondition('at-least-x-lands', [7], 8),
        ]);
//        $this->assistant->addConditionsPack('Has 1 land', [
//            $this->conditionFactory->getCondition('at-least-x-lands', [1], 1),
//        ]);
//        $this->assistant->addConditionsPack('Has 2 lands', [
//            $this->conditionFactory->getCondition('at-least-x-lands', [2], 2),
//        ]);
//        $this->assistant->addConditionsPack('Has 3 lands', [
//            $this->conditionFactory->getCondition('at-least-x-lands', [3], 3),
//        ]);
//        $this->assistant->addConditionsPack('Has 4 lands', [
//            $this->conditionFactory->getCondition('at-least-x-lands', [4], 4),
//        ]);
//        $this->assistant->addConditionsPack('Has 5 lands', [
//            $this->conditionFactory->getCondition('at-least-x-lands', [5], 5),
//        ]);
//        $this->assistant->addConditionsPack('Has 6 lands', [
//            $this->conditionFactory->getCondition('at-least-x-lands', [6], 6),
//        ]);
//        $this->assistant->addConditionsPack('Has 7 lands', [
//            $this->conditionFactory->getCondition('at-least-x-lands', [7], 7),
//        ]);

        $result = $this->assistant->runSimulations();

        $output->writeln(
            json_encode($result, JSON_PRETTY_PRINT)
        );

        return Command::SUCCESS;
    }
}
