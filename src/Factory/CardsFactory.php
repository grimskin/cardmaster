<?php


namespace App\Factory;


use App\Model\CardDefinition;

class CardsFactory
{
    const STUB_NAME = 'stub';

    private $dataLoader;
    private $stub;
    private $definitions = [];

    public function addDefinition(
        string $cardName
    )
    {
        $definition = CardDefinition::define($cardName);

        if ($cardData = $this->dataLoader->getDataByName($cardName)) {
            $definition->getData($cardData);
        }

        $this->definitions[$cardName] = $definition;
    }

    public function getCard(string $cardName): ?CardDefinition
    {
        if ($cardName == self::STUB_NAME) {
            return $this->getStub();
        } else {
            return $this->definitions[$cardName] ?? null;
        }
    }

    public function getStub(): CardDefinition
    {
        if (!$this->stub) {
            $this->stub = CardDefinition::define('-', true);
        }

        return $this->stub;
    }
}
