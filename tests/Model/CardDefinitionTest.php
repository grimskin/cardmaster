<?php


namespace App\Tests\Model;


use App\Model\CardData;
use App\Model\CardDefinition;
use PHPUnit\Framework\TestCase;

class CardDefinitionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldHandleMulticoloredLands()
    {
        $cardDef = $this->loadCardDefinition('Raugrin Triome');

        $this->assertTrue($cardDef->canProduceRed());
        $this->assertTrue($cardDef->canProduceWhite());
        $this->assertTrue($cardDef->canProduceBlue());
        $this->assertFalse($cardDef->canProduceGreen());
        $this->assertFalse($cardDef->canProduceBlack());
    }

    /**
     * @test
     */
    public function shouldHandleBasicLands()
    {
        $cardDef = $this->loadCardDefinition('Forest');

        $this->assertTrue($cardDef->isLand());
        $this->assertFalse($cardDef->canProduceRed());
        $this->assertFalse($cardDef->canProduceWhite());
        $this->assertFalse($cardDef->canProduceBlue());
        $this->assertTrue($cardDef->canProduceGreen());
        $this->assertFalse($cardDef->canProduceBlack());
    }

    /**
     * @test
     */
    public function shouldHandleFetches()
    {
        $cardDef = $this->loadCardDefinition('Fabled Passage');

        $this->assertTrue($cardDef->isLand());
        $this->assertFalse($cardDef->canProduceRed());
        $this->assertFalse($cardDef->canProduceWhite());
        $this->assertFalse($cardDef->canProduceBlue());
        $this->assertFalse($cardDef->canProduceGreen());
        $this->assertFalse($cardDef->canProduceBlack());
    }

    /**
     * @test
     */
    public function shouldRecognizeCreature()
    {
        $cardDef = $this->loadCardDefinition('Fabled Passage');
        $this->assertFalse($cardDef->isCreature());

        $cardDef = $this->loadCardDefinition('Anvilwrought Raptor');
        $this->assertTrue($cardDef->isCreature());

        $cardDef = $this->loadCardDefinition('Yorion, Sky Nomad');
        $this->assertTrue($cardDef->isCreature());
    }

    /**
     * @test
     */
    public function shouldReturnCreatureTypes()
    {
        $cardDef = $this->loadCardDefinition('Fabled Passage');
        $this->assertCount(0, $cardDef->getCreatureTypes());

        $cardDef = $this->loadCardDefinition('Anvilwrought Raptor');
        $this->assertCount(1, $cardDef->getCreatureTypes());
        $this->assertContains('Bird', $cardDef->getCreatureTypes());

        $cardDef = $this->loadCardDefinition('Yorion, Sky Nomad');
        $this->assertCount(2, $cardDef->getCreatureTypes());
        $this->assertContains('Bird', $cardDef->getCreatureTypes());
        $this->assertContains('Serpent', $cardDef->getCreatureTypes());
    }

    private function loadCardDefinition($cardName): ? CardDefinition {
        $cardDef = CardDefinition::define($cardName);
        $cardDef->absorbData(
                CardData::createFromDatum(json_decode(file_get_contents('tests/Fixtures/'.$cardName.'.json'), true))
            );

        return $cardDef;
    }
}
