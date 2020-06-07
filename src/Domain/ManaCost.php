<?php


namespace App\Domain;


use JsonSerializable;

class ManaCost implements JsonSerializable
{
    private $manaCostString = '';

    /** @var ManaCostItem[]  */
    private $manaItems = [];

    public function __construct(string $manaCostString)
    {
        $this->manaCostString = $manaCostString;
        $this->parseManaCost();
    }

    private function parseManaCost()
    {
        $cost = $this->manaCostString;

        while ($pos = strpos($cost, '}')) {
            $this->manaItems[] = new ManaCostItem(substr($cost, 0, $pos+1));
            $cost = substr($cost, $pos+1);
        }
    }

    public function __toString()
    {
        return $this->manaCostString;
    }

    public function jsonSerialize()
    {
        return $this->manaItems;
    }
}
