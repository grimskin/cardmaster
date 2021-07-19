<?php


namespace App\Tests\Fixtures;


use App\Model\CardData;
use App\Model\CardDefinition;

trait FixtureLoad
{
    private function loadCardDefinition($cardName): ? CardDefinition {
        $cardDef = CardDefinition::define($cardName);
        $cardDef->absorbData(
            CardData::createFromDatum(json_decode(file_get_contents('tests/Fixtures/'.$cardName.'.json'), true))
        );

        return $cardDef;
    }
}
