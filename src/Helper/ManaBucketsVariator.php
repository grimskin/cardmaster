<?php


namespace App\Helper;


use App\Domain\ManaBucket;
use App\Domain\ManaCost;
use App\Model\CardDefinition;

class ManaBucketsVariator
{
    /** @var ManaBucket[] */
    private array $buckets;

    /**
     * @param CardDefinition[] $cardDefinitions
     * @return ManaBucket[]
     */
    public function getLandBuckets(array $cardDefinitions): array
    {
        $this->buckets = [];

        $lands = array_filter($cardDefinitions, function (CardDefinition $item) {
            return $item->isLand();
        });

        $this->fillLandBuckets(new ManaBucket(), ...$lands);

        return [...$this->buckets];
    }

    /**
     * @param ManaBucket $bucket
     * @param CardDefinition[] $cardDefinitions
     * @return void
     */
    private function fillLandBuckets(ManaBucket $bucket, CardDefinition ...$cardDefinitions): void
    {
        if (!count($cardDefinitions)) {
            $this->buckets[$bucket->toString()] = $bucket;
            return;
        }

        $card = array_pop($cardDefinitions);

        $this->fillLandBuckets($bucket->copy()->putGeneric(), ...$cardDefinitions);

        if ($card->canProduce(CardDefinition::COLOR_WHITE)) {
            $this->fillLandBuckets($bucket->copy()->putWhite(), ...$cardDefinitions);
        }
        if ($card->canProduce(CardDefinition::COLOR_BLUE)) {
            $this->fillLandBuckets($bucket->copy()->putBlue(), ...$cardDefinitions);
        }
        if ($card->canProduce(CardDefinition::COLOR_BLACK)) {
            $this->fillLandBuckets($bucket->copy()->putBlack(), ...$cardDefinitions);
        }
        if ($card->canProduce(CardDefinition::COLOR_RED)) {
            $this->fillLandBuckets($bucket->copy()->putRed(), ...$cardDefinitions);
        }
        if ($card->canProduce(CardDefinition::COLOR_GREEN)) {
            $this->fillLandBuckets($bucket->copy()->putGreen(), ...$cardDefinitions);
        }

        unset($bucket);
    }

    /**
     * @param ManaCost $cost
     * @return ManaBucket[]
     */
    public function getCostBuckets(ManaCost $cost): array
    {
        $this->buckets = [];

        foreach ($cost->getCostVariants() as $variant) {
            $bucket = new ManaBucket();

            foreach ($variant as $item) {
                if ($item === 'W') $bucket->putWhite();
                elseif ($item === 'U') $bucket->putBlue();
                elseif ($item === 'B') $bucket->putBlack();
                elseif ($item === 'R') $bucket->putRed();
                elseif ($item === 'G') $bucket->putGreen();
                else $bucket->putGeneric(intval($item));
            }

            $this->buckets[$bucket->toString()] = $bucket;
        }

        return [...$this->buckets];
    }
}
