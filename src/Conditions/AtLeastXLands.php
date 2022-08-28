<?php

namespace App\Conditions;


use App\Model\CardDefinition;
use Exception;

class AtLeastXLands extends AbstractCondition
{
    public function getName(): string
    {
        return 'at-least-x-lands';
    }

    public function getReadableName(): string
    {
        return 'At least X lands';
    }

    public function testHand(CardDefinition ... $cardDefinitions): bool
    {
        $requireLands = (int) $this->params[0] ?? '';

        if (!$requireLands) {
            throw new Exception('Not enough params provided to '.$this->getName().' condition');
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
