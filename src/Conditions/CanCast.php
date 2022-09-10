<?php


namespace App\Conditions;


use App\Domain\ManaCost;
use App\Helper\CanCastCache;
use App\Helper\ManaBucketsVariator;
use App\Model\CardDefinition;
use Exception;

class CanCast extends AbstractCondition
{
    private ?CardDefinition $cardCache = null;
    private ?ManaCost $manaCostCache = null;
    private ?array $costBucketsCache = null;
    private ?CanCastCache $cache = null;

    public function getName(): string
    {
        return 'can-cast';
    }

    public function getReadableName(): string
    {
        return 'Can cast the card';
    }

    public function setCacheHandler(CanCastCache $cache)
    {
        $this->cache = $cache;
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

        if ($this->cache) {
            $cachedResult = $this->cache->getResult($this->manaCostCache, $cardDefinitions);

            if ($cachedResult !== null) return $cachedResult;
        }


        $bucketVariator = new ManaBucketsVariator();

        if (!$this->costBucketsCache) $this->costBucketsCache = $bucketVariator->getCostBuckets($this->manaCostCache);

        $landBuckets = $bucketVariator->getLandBuckets($cardDefinitions);

        foreach ($this->costBucketsCache as $costBucket) {
            foreach ($landBuckets as $landBucket) {
                if ($landBucket->contains($costBucket)) {
                    $this->cache?->saveResult($this->manaCostCache, $cardDefinitions, true);
                    return true;
                }
            }
        }

        $this->cache?->saveResult($this->manaCostCache, $cardDefinitions, false);
        return false;
    }
}
