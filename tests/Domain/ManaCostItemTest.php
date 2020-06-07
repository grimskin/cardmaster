<?php


namespace App\Tests\Domain;


use App\Domain\ManaCostItem;
use PHPUnit\Framework\TestCase;

class ManaCostItemTest extends TestCase
{
    /**
     * @test
     * @dataProvider costData
     *
     * @param string $costString
     * @param array $variants
     */
    public function shouldProvideProperVariants(string $costString, array $variants)
    {
        $item = new ManaCostItem($costString);

        $this->assertEquals($variants, $item->getItemVariants());
    }

    public function costData()
    {
        return [
            [
                '{W}',
                [['W']],
            ],
            [
                '{3}',
                [['3']]
            ],
            [
                '{W/U}',
                [['W'], ['U']]
            ],
        ];
    }
}
