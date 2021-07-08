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
}
