<?php


namespace App\Conditions;


use App\Factory\CardsFactory;
use App\Model\CardDefinition;
use Exception;

class CanCast extends AbstractCondition
{
    public function getName(): string
    {
        return 'can-cast';
    }

    public function testHand(CardDefinition ...$cardDefinitions): bool
    {
        $cardName = $this->params[0] ?? '';

        if (!$cardName) {
            throw new Exception('Not enough params provided to has-card condition');
        }

        $card = $this->cardsFactory->getCard($cardName);

        return false;
    }
}
