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
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('14', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 8);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 7);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('15g', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 7);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 8);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('15b', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 8);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 8);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('16', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 8);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 7);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('17g', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 7);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 8);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('17b', $deck);
        $deck = new DeckDefinition();
        $deck->addCards($this->cardsFactory->getCard('Forest'), 9);
        $deck->addCards($this->cardsFactory->getCard('Swamp'), 9);
        $deck->fillUpTo(40, $this->cardsFactory->getCard('stub'));
        $this->assistant->addDeck('18', $deck);


        $passCount = 1000000;

        $config = new ScenarioConfig();
        $config->setPassCount($passCount);

        $this->assistant->setConfig($config);

//        $this->assistant->addConditionsPack('1G', [
//            $this->conditionFactory->getCondition('can-cast', ['Llanowar Loamspeaker'], 2),
//        ]);
//        $this->assistant->addConditionsPack('GG', [
//            $this->conditionFactory->getCondition('can-cast', ['Leaf-Crowned Visionary'], 2),
//        ]);
//        $this->assistant->addConditionsPack('2G', [
//            $this->conditionFactory->getCondition('can-cast', ['Deathbloom Gardener'], 3),
//        ]);
//        $this->assistant->addConditionsPack('1BB', [
//            $this->conditionFactory->getCondition('can-cast', ['Liliana of the Veil'], 3),
//        ]);
        $this->assistant->addConditionsPack('3G', [
            $this->conditionFactory->getCondition('can-cast', ['Magnigoth Sentry'], 4),
        ]);
        $this->assistant->addConditionsPack('2BB', [
            $this->conditionFactory->getCondition('can-cast', ['Sheoldred, the Apocalypse'], 4),
        ]);
//        $this->assistant->addConditionsPack('4G', [
//            $this->conditionFactory->getCondition('can-cast', ['Elfhame Wurm'], 5),
//        ]);
//        $this->assistant->addConditionsPack('3GG', [
//            $this->conditionFactory->getCondition('can-cast', ['Defiler of Vigor'], 5),
//        ]);
//        $this->assistant->addConditionsPack('2GGG', [
//            $this->conditionFactory->getCondition('can-cast', ['Silverback Elder'], 5),
//        ]);
//        $this->assistant->addConditionsPack('5G', [
//            $this->conditionFactory->getCondition('can-cast', ['Briar Hydra'], 6),
//        ]);
//        $this->assistant->addConditionsPack('4BB', [
//            $this->conditionFactory->getCondition('can-cast', ['Tyrannical Pitlord'], 6),
//        ]);

        $result = $this->assistant->runSimulations();

        $output->writeln(
            json_encode($result, JSON_PRETTY_PRINT)
        );

        return Command::SUCCESS;
    }
}
