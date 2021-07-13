<?php


namespace App\Tests\Service\Set\Collector;


use App\Model\CardDefinition;
use App\Service\Set\Collector\LegendaryCounter;
use App\Tests\Fixtures\FixtureLoad;
use PHPUnit\Framework\TestCase;

class LegendaryCounterTest extends TestCase
{
    use FixtureLoad;

    /**
     * @test
     */
    public function shouldCountStats()
    {
        $fixtures = [
            $this->loadCardDefinition('Yorion, Sky Nomad'),
            $this->loadCardDefinition('Anvilwrought Raptor'),
            $this->loadCardDefinition('Hand of Vecna'),
            $this->loadCardDefinition('Trostani Discordant'),
            $this->loadCardDefinition('Professor Onyx'),
            $this->loadCardDefinition('Gretchen Titchwillow'),
        ];

        $counter = LegendaryCounter::fromCards($fixtures);

        $this->assertEquals(5, $counter->getTotal());

        $stats = $counter->getBreakdown();

        $this->assertCount(4, $stats);
        $this->assertArrayHasKey(CardDefinition::T_CREATURE, $stats);
        $this->assertArrayHasKey(CardDefinition::T_ARTIFACT, $stats);
        $this->assertArrayHasKey(CardDefinition::T_PLANESWALKER, $stats);
        $this->assertEquals(1, $stats[CardDefinition::T_CREATURE][CardDefinition::R_MYTHIC]);
        $this->assertEquals(1, $stats[CardDefinition::T_CREATURE][CardDefinition::R_RARE]);
        $this->assertEquals(1, $stats[CardDefinition::T_CREATURE][CardDefinition::R_UNCOMMON]);
        $this->assertEquals(3, $stats[CardDefinition::T_CREATURE]['Total']);
        $this->assertEquals(CardDefinition::T_CREATURE, array_key_first($stats));
        $this->assertEquals($counter->getTotal(), $stats['']['Total']);
    }
}
