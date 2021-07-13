<?php


namespace App\Tests\Service\Set\Collector;


use App\Service\Set\Collector\CreatureStats;
use App\Tests\Fixtures\FixtureLoad;
use PHPUnit\Framework\TestCase;

class CreatureStatsTest extends TestCase
{
    use FixtureLoad;

    /**
     * @test
     */
    public function shouldCalculateAverageValues()
    {
        $counter = CreatureStats::fromCards([
            $this->loadCardDefinition('Yorion, Sky Nomad'), // 4/5 for 5
            $this->loadCardDefinition('Professor Onyx'), // not a creature
            $this->loadCardDefinition('Anvilwrought Raptor'), // 2/1 for 4
            $this->loadCardDefinition('Trostani Discordant'), // 1/4 for 5
        ]);

        $this->assertEquals(3, $counter->getCreaturesCount());

        $this->assertEquals(2.33, round($counter->getAveragePower(), 2));
        $this->assertEquals(3.33, round($counter->getAverageToughness(), 2));
        $this->assertEquals(4.67, round($counter->getAverageManaValue(), 2));
    }

    /**
     * @test
     */
    public function shouldCalculateAsterisks()
    {
        $counter = CreatureStats::fromCards([
            $this->loadCardDefinition('Yorion, Sky Nomad'),
            $this->loadCardDefinition('Professor Onyx'),
            $this->loadCardDefinition('Ashaya, Soul of the Wild'),
            $this->loadCardDefinition('Anvilwrought Raptor'),
            $this->loadCardDefinition('Trostani Discordant'),
        ]);

        $this->assertEquals(1, $counter->getAsteriskAnyCount());
    }

    /**
     * @test
     */
    public function shouldBreakdownPower()
    {
        $counter = CreatureStats::fromCards([
            $this->loadCardDefinition('Yorion, Sky Nomad'), // 4/5 for 5
            $this->loadCardDefinition('Professor Onyx'), // not a creature
            $this->loadCardDefinition('Anvilwrought Raptor'), // 2/1 for 4
            $this->loadCardDefinition('Trostani Discordant'), // 1/4 for 5
            $this->loadCardDefinition('Gretchen Titchwillow'), // 0/4 for 2
        ]);

        $stats = $counter->getPowerBreakdown();
        $this->assertCount(5, $stats);

        $this->assertEquals(1, $stats[0]);
        $this->assertEquals(1, $stats[1]);
        $this->assertEquals(1, $stats[2]);
        $this->assertEquals(0, $stats[3]);
        $this->assertEquals(1, $stats[4]);
    }

    /**
     * @test
     */
    public function shouldBreakdownToughness()
    {
        $counter = CreatureStats::fromCards([
            $this->loadCardDefinition('Yorion, Sky Nomad'), // 4/5 for 5
            $this->loadCardDefinition('Professor Onyx'), // not a creature
            $this->loadCardDefinition('Anvilwrought Raptor'), // 2/1 for 4
            $this->loadCardDefinition('Trostani Discordant'), // 1/4 for 5
            $this->loadCardDefinition('Gretchen Titchwillow'), // 0/4 for 2
        ]);

        $stats = $counter->getToughnessBreakdown();
        $this->assertCount(6, $stats);

        $this->assertEquals(0, $stats[0]);
        $this->assertEquals(1, $stats[1]);
        $this->assertEquals(0, $stats[2]);
        $this->assertEquals(0, $stats[3]);
        $this->assertEquals(2, $stats[4]);
        $this->assertEquals(1, $stats[5]);
    }

    /**
     * @test
     */
    public function shouldBreakdownManaValue()
    {
        $counter = CreatureStats::fromCards([
            $this->loadCardDefinition('Yorion, Sky Nomad'), // 4/5 for 5
            $this->loadCardDefinition('Professor Onyx'), // not a creature
            $this->loadCardDefinition('Anvilwrought Raptor'), // 2/1 for 4
            $this->loadCardDefinition('Trostani Discordant'), // 1/4 for 5
            $this->loadCardDefinition('Gretchen Titchwillow'), // 0/4 for 2
        ]);

        $stats = $counter->getManaValueBreakdown();
        $this->assertCount(6, $stats);

        $this->assertEquals(0, $stats[0]);
        $this->assertEquals(0, $stats[1]);
        $this->assertEquals(1, $stats[2]);
        $this->assertEquals(0, $stats[3]);
        $this->assertEquals(1, $stats[4]);
        $this->assertEquals(2, $stats[5]);
    }
}
