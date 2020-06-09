<?php


namespace App\Domain;


class ManaPool
{
    const WHITE = 'W';
    const BLUE = 'U';
    const BLACK = 'B';
    const RED = 'R';
    const GREEN = 'G';
    const GENERIC = 'ANY';

    private $pool = [
        self::WHITE => 0,
        self::BLUE => 0,
        self::BLACK => 0,
        self::RED => 0,
        self::GREEN => 0,
        self::GENERIC => 0,
    ];


    public function addMana(string $manaString)
    {
        if (preg_match("'^[WUBRG]$'", $manaString)) {
            $this->pool[$manaString]++;

            return;
        }

        if (preg_match("'^[0-9]$'", $manaString)) {
            $this->pool[self::GENERIC] += (int) $manaString;

            return;
        }
    }

    public static function fromArray(array $mana): self
    {
        $result = new self();

        foreach ($mana as $manaString) {
            $result->addMana($manaString);
        }

        return $result;
    }

    public function totalMana(): int
    {
        return array_reduce($this->pool, function ($carry, $item) {
            return $carry + $item;
        });
    }
}
