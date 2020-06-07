<?php


namespace App\Domain;


use JsonSerializable;

class ManaCostItem implements JsonSerializable
{
    private $itemString;
    private $cleanString;

    public function __construct(string $itemString)
    {
        $this->itemString = $itemString;
        $this->cleanString = substr($itemString, 1, -1);
    }

    public function jsonSerialize()
    {
        return $this->cleanString;
    }
}
