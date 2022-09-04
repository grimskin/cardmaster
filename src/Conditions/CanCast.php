<?php


namespace App\Conditions;


use App\Domain\ManaCost;
use App\Domain\ManaPool;
use App\Helper\ManaVariator;
use App\Model\CardDefinition;
use Exception;

class CanCast extends AbstractCondition
{
    private ?CardDefinition $cardCache = null;
    private ?ManaCost $manaCostCache = null;

    public function getName(): string
    {
        return 'can-cast';
    }

    public function getReadableName(): string
    {
        return 'Can cast the card';
    }

    public function testHand(CardDefinition ...$cardDefinitions): bool
    {
        $cardName = $this->params[0] ?? '';

        if (!$cardName) {
            throw new Exception('Not enough params provided to has-card condition');
        }

        if (!$this->cardCache) {
            $this->cardCache = $this->cardsFactory->getCard($cardName);
            $this->manaCostCache = new ManaCost($this->cardCache->getManaCost());
        }

        $manaOptions = ManaVariator::getManaOptions(...$cardDefinitions);

        foreach ($manaOptions as $manaOption) {
            $manaPool = ManaPool::fromArray($manaOption);

            if ($manaPool->canPayFor($this->manaCostCache)) {
                return true;
            }
        }

        return false;
    }
}
