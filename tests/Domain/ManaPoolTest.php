<?php


namespace App\Tests\Domain;


use App\Domain\ManaCost;
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

    /**
     * @test
     * @dataProvider payCheckProvider
     *
     * @param array $manaPool
     * @param string $manaCost
     * @param bool $canPay
     */
    public function shouldProperlyCheckIfCanPay(array $manaPool, string $manaCost, bool $canPay)
    {
        $manaPool = ManaPool::fromArray($manaPool);
        $manaCost = new ManaCost($manaCost);

        $this->assertEquals($canPay, $manaPool->canPayFor($manaCost));
    }

    public function payCheckProvider(): array
    {
        return [
            [
                ['U', 'U', 'W'],
                '{1}{W}{U}',
                true,
            ],
            [
                ['U', 'U', 'U'],
                '{1}{W}{U}',
                false,
            ],
            [
                ['U', 'W'],
                '{1}{W}{U}',
                false,
            ],
            [
                ['W', 'U', '5'],
                '{3}{W/U}{W/U}',
                true,
            ],
            [
                ['W', 'U', '1', '2'],
                '{3}{W/U}{W/U}',
                true,
            ],
        ];
    }
}
