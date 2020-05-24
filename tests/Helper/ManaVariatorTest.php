<?php


namespace App\Tests\Helper;

use App\Helper\ManaVariator;
use App\Model\CardData;
use App\Model\CardDefinition;
use PHPUnit\Framework\TestCase;

class ManaVariatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider manaOptionsProvider
     *
     * @param CardDefinition[] $cardDefinitions
     * @param array $manaOptions
     */
    public function shouldGeneratePossibleOptions(array $cardDefinitions, array $manaOptions)
    {
        $this->assertEquals($manaOptions, ManaVariator::getManaOptions(...$cardDefinitions));
    }

    public function manaOptionsProvider()
    {
        return [
            'single forest' => [
                [ $this->loadCardDefinition('Forest'), ],
                [ ['1'], ['G'] ],
            ],
            'double forests' => [
                [
                    $this->loadCardDefinition('Forest'),
                    $this->loadCardDefinition('Forest'),
                ],
                [
                    ['1', '1'],
                    ['1', 'G'],
                    ['G', '1'],
                    ['G', 'G'],
                ],
            ],
            'triome and fetch' => [
                [
                    $this->loadCardDefinition('Raugrin Triome'),
                    $this->loadCardDefinition('Fabled Passage'),
                ],
                [
                    ['1', '1'],
                    ['1', 'W'],
                    ['1', 'U'],
                    ['1', 'R'],
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function shouldReturnProperManaOptionsForSingleCard()
    {
        $calculatedOptions = ManaVariator::getLandManaOptions($this->loadCardDefinition('Forest'));
        $this->assertEquals(['1', 'G'], $calculatedOptions);

        $calculatedOptions = ManaVariator::getLandManaOptions($this->loadCardDefinition('Fabled Passage'));
        $this->assertEquals(['1'], $calculatedOptions);

        $calculatedOptions = ManaVariator::getLandManaOptions($this->loadCardDefinition('Raugrin Triome'));
        $this->assertEquals(['1', 'W', 'U', 'R'], $calculatedOptions);
    }

    private function loadCardDefinition($cardName): ? CardDefinition {
        $cardDef = CardDefinition::define($cardName);
        $cardDef->absorbData(
            CardData::createFromDatum(json_decode(file_get_contents('tests/Fixtures/'.$cardName.'.json'), true))
        );

        return $cardDef;
    }
}
