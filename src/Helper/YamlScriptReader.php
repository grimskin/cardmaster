<?php


namespace App\Helper;


use App\Domain\TestAssistant;
use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Model\DeckDefinition;
use App\Scenarios\ScenarioConfig;
use Symfony\Component\Yaml\Yaml;

class YamlScriptReader
{
    private string $scriptsDir;
    private CardsFactory $cardsFactory;
    private ConditionFactory $conditionFactory;

    public function __construct(string $projectDir, CardsFactory $cardsFactory, ConditionFactory $conditionFactory)
    {
        $this->scriptsDir = $projectDir . '/scripts/';
        $this->cardsFactory = $cardsFactory;
        $this->conditionFactory = $conditionFactory;
    }

    public function hasFile(string $fileName): bool
    {
        return file_exists($this->scriptsDir . $fileName . '.yaml');
    }

    public function configureFromFile(TestAssistant $assistant, string $fileName): void
    {
        $data = Yaml::parseFile($this->scriptsDir . $fileName . '.yaml');

        $this->configureDecks($assistant, $data['decks']);
        $this->configureConditions($assistant, $data['conditions']);

        $config = new ScenarioConfig();
        if (isset($data['passes'])) $config->setPassCount((int)$data['passes']);

        $assistant->setConfig($config);
    }

    private function configureConditions(TestAssistant $assistant, array $packs): void
    {
        foreach ($packs as $pack) {
            $conditionPack = [];
            foreach ($pack['conditions'] as $condition) {
                $conditionPack[] = $this->conditionFactory->getCondition(
                    $condition['type'], [$condition['param'] ?? []], $condition['turn'] ?? 1
                );
                $assistant->addConditionsPack($pack['name'], $conditionPack);
            }
        }
    }

    private function configureDecks(TestAssistant $assistant, array $decks): void
    {
        foreach ($decks as $deckData) {
            $deck = new DeckDefinition();
            foreach ($deckData['cards'] as $cardData) {
                $deck->addCards($this->cardsFactory->getCard($cardData['name']), $cardData['amount']);
            }
            if (isset($deckData['fill-to']))
                $deck->fillUpTo((int)$deckData['fill-to'], $this->cardsFactory->getCard('stub'));

            $assistant->addDeck($deckData['name'], $deck);
        }
    }
}
