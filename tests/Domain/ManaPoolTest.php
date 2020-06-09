<?php


namespace App\Tests\Domain;


use App\Domain\ManaPool;
use PHPUnit\Framework\TestCase;

class ManaPoolTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCalculateMana()
    {
        $pool = ManaPool::fromArray(['3', 'W', 'U', 'B', 'R', 'G', 'G']);

        $this->assertEquals(9, $pool->totalMana());
    }
}
