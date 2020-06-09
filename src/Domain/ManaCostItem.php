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

    public function getItemVariants()
    {
        if (strlen($this->cleanString) == 1) {
            return [ [$this->cleanString] ];
        }

        if (preg_match("'^[WUBRG]/[WUBRG]$'", $this->cleanString)) {
            list($color1, $color2) = explode('/', $this->cleanString);
            return [[$color1], [$color2]];
        }

        return [];
    }

    public function jsonSerialize()
    {
        return $this->cleanString;
    }
}
