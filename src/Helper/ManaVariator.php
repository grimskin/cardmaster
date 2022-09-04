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
                $result[] = [$manaOption, ...$option];
            }
        }

        return $result;
    }

    public static function getLandManaOptions(CardDefinition $card): array
    {
        return array_values(array_filter([
            '1',
            $card->canProduce(CardDefinition::COLOR_WHITE) ? CardDefinition::COLOR_WHITE : null,
            $card->canProduce(CardDefinition::COLOR_BLUE) ? CardDefinition::COLOR_BLUE : null,
            $card->canProduce(CardDefinition::COLOR_BLACK) ? CardDefinition::COLOR_BLACK : null,
            $card->canProduce(CardDefinition::COLOR_RED) ? CardDefinition::COLOR_RED : null,
            $card->canProduce(CardDefinition::COLOR_GREEN) ? CardDefinition::COLOR_GREEN : null,
        ]));
    }
}
