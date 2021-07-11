<?php


namespace App\Tests\Service\Set\Collector;


use App\Service\Set\Collector\CreatureTypes;
use App\Tests\Fixtures\FixtureLoad;
use PHPUnit\Framework\TestCase;

class CreatureTypesTest extends TestCase
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
        ];

        $stats = CreatureTypes::fromCards($fixtures)->getStats();

        $this->assertCount(2, $stats);
        $this->assertArrayHasKey('Bird', $stats);
        $this->assertArrayHasKey('Serpent', $stats);
        $this->assertEquals(2, $stats['Bird']);
        $this->assertEquals(1, $stats['Serpent']);
        $this->assertEquals('Bird', array_key_first($stats));
    }

    /**
     * @test
     */
    public function shouldSortOneOffs()
    {
        $fixtures = [
            $this->loadCardDefinition('Yorion, Sky Nomad'),
            $this->loadCardDefinition('Anvilwrought Raptor'),
            $this->loadCardDefinition('Trostani Discordant'),
        ];

        $oneOffs = CreatureTypes::fromCards($fixtures)->getOneOffs();
        $this->assertCount(2, $oneOffs);
        $this->assertContains('Dryad', $oneOffs);
        $this->assertContains('Serpent', $oneOffs);
        $this->assertEquals('Dryad', $oneOffs[0]);
    }

    /**
     * @test
     */
    public function shouldBreakDownTypesCounts()
    {
        $fixtures = [
            $this->loadCardDefinition('Yorion, Sky Nomad'),
            $this->loadCardDefinition('Anvilwrought Raptor'),
            $this->loadCardDefinition('Trostani Discordant'),
        ];

        $typesCounts = CreatureTypes::fromCards($fixtures)->getTypesOnCreature();
        $this->assertCount(2, $typesCounts);
        $this->assertEquals(1, $typesCounts[2]); // one creature with 2 types - Yorion
        $this->assertEquals(2, $typesCounts[1]); // two creatures with 1 types
        $this->assertEquals(2, array_key_first($typesCounts)); // Results are sorted in descending order
    }
}
