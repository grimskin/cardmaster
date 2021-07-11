<?php


namespace App\Tests\Model;


use App\Model\CardDefinition;
use App\Tests\Fixtures\FixtureLoad;
use PHPUnit\Framework\TestCase;

class CardDefinitionTest extends TestCase
{
    use FixtureLoad;

    /**
     * @test
     */
    public function shouldHandleMulticoloredLands()
    {
        $cardDef = $this->loadCardDefinition('Raugrin Triome');

        $this->assertTrue($cardDef->canProduce(CardDefinition::COLOR_RED));
        $this->assertTrue($cardDef->canProduce(CardDefinition::COLOR_WHITE));
        $this->assertTrue($cardDef->canProduce(CardDefinition::COLOR_BLUE));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_GREEN));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_BLACK));
    }

    /**
     * @test
     */
    public function shouldHandleBasicLands()
    {
        $cardDef = $this->loadCardDefinition('Forest');

        $this->assertTrue($cardDef->isLand());
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_RED));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_WHITE));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_BLUE));
        $this->assertTrue($cardDef->canProduce(CardDefinition::COLOR_GREEN));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_BLACK));
    }

    /**
     * @test
     */
    public function shouldHandleFetches()
    {
        $cardDef = $this->loadCardDefinition('Fabled Passage');

        $this->assertTrue($cardDef->isLand());
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_RED));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_WHITE));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_BLUE));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_GREEN));
        $this->assertFalse($cardDef->canProduce(CardDefinition::COLOR_BLACK));
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
    public function shouldRecognizeLegendaries()
    {
        $cardDef = $this->loadCardDefinition('Anvilwrought Raptor');
        $this->assertFalse($cardDef->isLegendary());

        $cardDef = $this->loadCardDefinition('Trostani Discordant');
        $this->assertTrue($cardDef->isLegendary());
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

    /**
     * @test
     */
    public function shouldRecognizeType()
    {
        $cardDef = $this->loadCardDefinition('Yorion, Sky Nomad');

        $this->assertTrue($cardDef->isOfType(CardDefinition::T_CREATURE));
        $this->assertFalse($cardDef->isOfType(CardDefinition::T_ENCHANTMENT));
    }
}
