<?php


namespace App\Tests\Helper;


use App\Helper\ScriptParamsParser;
use PHPUnit\Framework\TestCase;

class ScriptParamsParserTest extends TestCase
{
    /**
     * @test
     * @dataProvider parseStringsProvider
     *
     * @param string $paramString
     * @param array $expectedResult
     */
    public function shouldParseParamString(string $paramString, array $expectedResult)
    {
        $actualResult = ScriptParamsParser::unpackParams($paramString);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function parseStringsProvider(): array
    {
        return [
            ['3, G', ['3', 'G']],
            ['has-card, [Trostani Discordant]', ['has-card', 'Trostani Discordant']],
            ['has-card, [Niv-Mizzet, Parun]', ['has-card', 'Niv-Mizzet, Parun']],
            [' [Niv-Mizzet, Parun], 3 ', ['Niv-Mizzet, Parun', '3']],
        ];
    }
}
