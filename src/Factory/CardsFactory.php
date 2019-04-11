<?php


namespace App\Factory;


use App\Model\CardDefinition;
use App\Service\DataLoader;

class CardsFactory
{
    const STUB_NAME = 'stub';

    private $dataLoader;
    private $stub;
    private $definitions = [];

    public function __construct(
        DataLoader $dataLoader
    ) {
        $this->dataLoader = $dataLoader;
    }

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
            return $this->definitions[$cardName] ?? $this->getDataFromLoader($cardName);
        }
    }

    public function getStub(): CardDefinition
    {
        if (!$this->stub) {
            $this->stub = CardDefinition::define('-', true);
        }

        return $this->stub;
    }

    private function getDataFromLoader($cardName)
    {
        $cardData = $this->dataLoader->getDataByName($cardName);

        if (!$cardData) {
            return null;
        }

        $definition = new CardDefinition();
        $definition->getData($cardData);
        $this->definitions[$cardName] = $definition;

        return $definition;
    }
}
