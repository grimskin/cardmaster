<?php


namespace App\Conditions;


use App\Model\CardDefinition;

class HasThreeLands implements ConditionInterface
{
    public function getName(): string
    {
        return 'has-three-lands';
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
