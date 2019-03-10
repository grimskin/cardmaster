<?php


namespace App\Factory;


use App\Model\CardDefinition;
use App\Service\DataLoader;

class CardsFactory
{
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
        return $this->definitions[$cardName] ?? null;
    }

    public function getStub(): CardDefinition
    {
        if (!$this->stub) {
            $this->stub = CardDefinition::define('-', true);
        }

        return $this->stub;
    }
}
