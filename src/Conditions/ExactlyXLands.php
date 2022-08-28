<?php


namespace App\Conditions;


use App\Model\CardDefinition;
use Exception;

class ExactlyXLands extends AbstractCondition
{
    public function getName(): string
    {
        return 'exactly-x-lands';
    }

    public function getReadableName(): string
    {
        return 'Has exactly X lands';
    }

    public function testHand(CardDefinition ... $cardDefinitions): bool
    {
        $this->hasIntParamOrThrow();

        $requireLands = $this->getIntParam();

        $landsCount = 0;

        foreach ($cardDefinitions as $card) {
            if ($card->isLand()) {
                $landsCount++;
            }
        }

        return $requireLands === $landsCount;
    }
}
