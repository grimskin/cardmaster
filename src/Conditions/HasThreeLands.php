<?php


namespace App\Conditions;


use App\Model\CardDefinition;

class HasThreeLands
{
    public function getRequiredHandSize(): int
    {
        return 7;
    }

    public function testHand(CardDefinition ... $cardDefinitions): bool
    {
        $landsCount = 0;

        foreach ($cardDefinitions as $card) {
            if ($card->isLand()) {
                $landsCount++;
            }
        }

        return $landsCount >= 3;
    }
}
