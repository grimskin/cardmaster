<?php


namespace App\Helper;


use App\Model\CardDefinition;

class ManaVariator
{
    public static function getManaOptions(CardDefinition ...$cardDefinitions): array
    {
        $lands = array_filter($cardDefinitions, function (CardDefinition $item) {
            return $item->isLand();
        });

        return self::manaOptionsGenerator(...$lands);
    }

    private static function manaOptionsGenerator(CardDefinition ...$cardDefinitions): array
    {
        if (!count($cardDefinitions)) { return []; }

        $result = [];

        $card = array_pop($cardDefinitions);

        $cardManaOptions = self::getLandManaOptions($card);

        if (!count($cardDefinitions)) {
            return array_map(function($item) {
                return [$item];
            }, $cardManaOptions);
        }

        foreach ($cardManaOptions as $manaOption) {
            foreach (self::manaOptionsGenerator(...$cardDefinitions) as $option) {
                $result[] = array_merge([$manaOption], $option);
            }
        }

        return $result;
    }

    public static function getLandManaOptions(CardDefinition $card)
    {
        return array_values(array_filter([
            '1',
            $card->canProduceWhite() ? 'W' : null,
            $card->canProduceBlue() ? 'U' : null,
            $card->canProduceBlack() ? 'B' : null,
            $card->canProduceRed() ? 'R' : null,
            $card->canProduceGreen() ? 'G' : null,
        ]));
    }
}
