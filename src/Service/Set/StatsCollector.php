<?php


namespace App\Service\Set;


class StatsCollector
{
    private array $cards;

    public function addCards(array $cards)
    {
        $this->cards = $cards;
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}
