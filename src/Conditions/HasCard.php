<?php


namespace App\Conditions;


use App\Model\CardDefinition;
use Exception;

class HasCard extends AbstractCondition
{
    public function getName(): string
    {
        return 'has-card';
    }

    public function testHand(CardDefinition ... $cardDefinitions): bool
    {
        $cardName = $this->params[0] ?? '';

        if (!$cardName) {
            throw new Exception('Not enough params provided to has-card condition');
        }

        $cardName = trim(strtolower($cardName));

        foreach ($cardDefinitions as $card) {
            if ($card->getCanonizedName() == $cardName) {
                return true;
            }
        }

        return false;
    }
}
