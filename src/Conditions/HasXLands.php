<?php


namespace App\Conditions;


use App\Model\CardDefinition;
use Exception;

class HasXLands extends AbstractCondition
{
    public function getName(): string
    {
        return 'has-x-lands';
    }

    public function testHand(CardDefinition ... $cardDefinitions): bool
    {
        $requireLands = (int) $this->params[0] ?? '';

        if (!$requireLands) {
            throw new Exception('Not enough params provided to has-x-lands condition');
        }

        $landsCount = 0;

        foreach ($cardDefinitions as $card) {
            if ($card->isLand()) {
                $landsCount++;
            }

            if ($requireLands == $landsCount) {
                return true;
            }
        }

        return false;
    }
}
