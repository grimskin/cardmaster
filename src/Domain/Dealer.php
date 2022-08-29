<?php


namespace App\Domain;


use App\Conditions\AtLeastXLands;
use App\Conditions\AtMostXLands;
use App\Model\CardDefinition;
use App\Model\Library;
use Exception;

class Dealer
{
    /**
     * @var string[]
     */
    private array $cardsOfInterest = [];
    private bool $isDebugMode = false;
    private int $requiredHandSize = 7;

    public function debugMode(bool $isDebugMode = true): void
    {
        $this->isDebugMode = $isDebugMode;
    }

    public function addCardOfInterest($cardName): void
    {
        $this->cardsOfInterest[$cardName] = $cardName;
    }

    public function getRequiredHandSize(): int
    {
        return $this->requiredHandSize;
    }


    public function getStartingHand(Library $library, int $handSize = 7): array
    {
        $library->reset();
        $library->shuffle();

        $hand = $library->drawHand($this->getRequiredHandSize());

        $minCondition = new AtLeastXLands();
        $maxCondition = new AtMostXLands();

        switch ($handSize) {
            case 7:
            case 6:
            case 5:
                // we are fine with hand that have at least 2 lands and at least 2 spells
                $minCondition->addParams([2]);
                $maxCondition->addParams([5]);
                if (!$minCondition->testHand(...$hand) || !$maxCondition->testHand(...$hand)) {
                    if ($this->isDebugMode) {
                        echo('Discarding    ' . count($hand) . ' - [' . $this->handToString($hand) . ']' . "\r\n");
                    }
                    return $this->getStartingHand($library, $handSize - 1);
                }
                break;
            case 4:
                break;
            default:
                throw new Exception('Unsupported hand size of '.$handSize.' in AbstractScenario::getStartingHand');
        }

        if ($handSize === $this->getRequiredHandSize()) return $hand;

        if ($this->isDebugMode) {
            echo('Pre-mulligan  ' . count($hand) . ' - [' . $this->handToString($hand) . ']' . "\r\n");
        }
        $postMulliganHand = $this->mulligan($hand, $library, $handSize);
        if ($this->isDebugMode) {
            echo('Post-mulligan ' . count($postMulliganHand) . ' - [' . $this->handToString($postMulliganHand) . ']' . "\r\n");
        }
        return $postMulliganHand;
    }

    protected function mulligan(array $hand, Library $library, int $targetSize): array
    {
        $atMostCondition = new AtMostXLands();

        if ($targetSize === 7) return $hand;

        // We have to bottom. Ideal would be 3 land, 3 spells
        $atMostCondition->addParams([3]);
        if ($atMostCondition->testHand(...$hand)) {
            $hand = $this->mulliganSpell($hand, $library);
        } else {
            $hand = $this->mulliganLand($hand, $library);
        }

        if ($targetSize === 6) return $hand;

        // We have to bottom. Ideal would be 3 land, 2 spells
        if ($atMostCondition->testHand(...$hand)) {
            $hand = $this->mulliganSpell($hand, $library);
        } else {
            $hand = $this->mulliganLand($hand, $library);
        }

        if ($targetSize === 5) return $hand;

        // We have to bottom. Ideal would be 3 land, 1 spell
        if ($atMostCondition->testHand(...$hand)) {
            $hand = $this->mulliganSpell($hand, $library);
        } else {
            $hand = $this->mulliganLand($hand, $library);
        }

        return $hand;
    }

    /**
     * @param CardDefinition[] $hand
     * @param Library $library
     * @return array
     */
    protected function mulliganLand(array $hand, Library $library): array
    {
        if ($this->isDebugMode) {
            echo('Mulligan [L] of   [' . $this->handToString($hand) . ']' . "\r\n");
        }

        $minColorProduced = 6;
        $positionToMulligan = -1;

        foreach ($hand as $position=>$card) {
            if (!$card->isLand()) continue;

            if (count($card->getColorIdentity()) < $minColorProduced) {
                $minColorProduced = count($card->getColorIdentity());
                $positionToMulligan = $position;
            }
        }

        $library->putOnBottom($hand[$positionToMulligan]);
        unset($hand[$positionToMulligan]);

        return array_values($hand);
    }

    /**
     * @param CardDefinition[] $hand
     * @param Library $library
     * @return array
     */
    protected function mulliganSpell(array $hand, Library $library): array
    {
        if ($this->isDebugMode) {
            echo('Mulligan [S] of   [' . $this->handToString($hand) . ']' . "\r\n");
        }
        // try to remove stubs
        foreach ($hand as $position=>$card) {
            if (!$card->isStub()) continue;

            $library->putOnBottom($card);
            unset($hand[$position]);

            return array_values($hand);
        }

        // try to remove irrelevant spell
        foreach ($hand as $position=>$card) {
            if (in_array($card->getName(), $this->cardsOfInterest)) continue;

            $library->putOnBottom($card);
            unset($hand[$position]);

            return array_values($hand);
        }

        // removing most expensive spell
        $maxCost = -1;
        $maxCostPosition = -1;

        foreach ($hand as $position=>$card) {
            if ($card->getManaValue() <= $maxCost) continue;

            $maxCost = $card->getManaValue();
            $maxCostPosition = $position;
        }

        if ($maxCost != -1) {
            $library->putOnBottom($hand[$maxCostPosition]);
            unset($hand[$maxCostPosition]);

            return array_values($hand);
        }

        // we got some weird cards, remove first non-land
        foreach ($hand as $position=>$card) {
            if ($card->isLand()) continue;

            $library->putOnBottom($card);
            unset($hand[$position]);

            return array_values($hand);
        }

        // wtf has happened?
        return $hand;
    }

    /**
     * @param CardDefinition[] $cardDefinitions
     * @return string
     */
    private function handToString(array $cardDefinitions): string
    {
        return implode(', ', array_map(function(CardDefinition $card) {
            return $card->getName();
        }, $cardDefinitions));
    }
}
