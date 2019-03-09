<?php


namespace App\Factory;


use App\Model\CardDefinition;

class CardsFactory
{
    private $stub;
    private $definitions = [];

    public function addDefinition(
        string $definitionName,
        string $cardName
    )
    {
        $this->definitions[$definitionName] = CardDefinition::define($cardName);
    }

    public function getCard(string $definitionName): ?CardDefinition
    {
        return $this->definitions[$definitionName] ?? null;
    }

    public function getStub(): CardDefinition
    {
        if (!$this->stub) {
            $this->stub = CardDefinition::define('-', true);
        }

        return $this->stub;
    }
}
