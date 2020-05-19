<?php


namespace App\Service;


use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\DeckDefinition;
use Exception;

class ScriptRunner
{
    private const CMD_ADD_CARD = 'card';
    private const CMD_SET_SCENARIO = 'scenario';
    private const CMD_ADD_CONDITION = 'condition';
    private const CMD_SET_PASS_COUNT = 'passes';

    private $parser;
    private $cardsFactory;
    private $conditionFactory;
    private $scenarioFactory;
    private $collector;

    public function __construct(
        CardsFactory $cardsFactory,
        ConditionFactory $conditionFactory,
        ScenarioFactory $scenarioFactory,
        StatsCollector $collector
    ) {
        $this->parser = new ScriptParser();
        $this->cardsFactory = $cardsFactory;
        $this->conditionFactory = $conditionFactory;
        $this->scenarioFactory = $scenarioFactory;
        $this->collector = $collector;
    }

    public function runScript(array $script)
    {
        $deck = new DeckDefinition();

        foreach ($script as $row) {
            if ($this->parser->isComment($row)) {
                continue;
            }

            $command = $this->parser->getCommand($row);

            switch ($command) {
                case self::CMD_ADD_CARD:
                    $deck->addCards(
                        $this->cardsFactory->getCard($this->parser->getCardName($row)),
                        $this->parser->getCardAmount($row)
                    );
                    break;
                case self::CMD_SET_SCENARIO:
                    $this->collector->setScenario(
                        $this->scenarioFactory->getScenario($this->parser->getScenarioName($row))
                    );
                    break;
                case self::CMD_ADD_CONDITION:
                    $this->collector->addCondition(
                        $this->conditionFactory->getCondition(
                            $this->parser->getConditionName($row),
                            $this->parser->getConditionParams($row)
                        )
                    );
                    break;
                case self::CMD_SET_PASS_COUNT:
                    $this->collector->setPassCount($this->parser->getPassCount($row));
                    break;
                default:
                    throw new Exception('Unknown command: ' . $command);
            }
        }

        $this->collector->setDeck($deck);

        return $this->collector->runSimulation();
    }
}
