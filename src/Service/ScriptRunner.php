<?php


namespace App\Service;


use App\Factory\CardsFactory;
use App\Model\DeckDefinition;

class ScriptRunner
{
    private const CMD_ADD_CARD = 'card';

    private $parser;
    private $cardsFactory;

    public function __construct(
        CardsFactory $cardsFactory
    ) {
        $this->parser = new ScriptParser();
        $this->cardsFactory = $cardsFactory;
    }

    public function runScript(array $script)
    {
        $deck = new DeckDefinition();

        foreach ($script as $row) {
            $command = $this->parser->getCommand($row);

            switch ($command) {
                case self::CMD_ADD_CARD:
                    $deck->addCards(
                        $this->cardsFactory->getCard($this->parser->getCardName($row)),
                        $this->parser->getCardAmount($row)
                    );
                    break;
            }


        }
    }
}
