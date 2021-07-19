<?php


namespace App\Service\Set\Collector;


use App\Model\CardDefinition;

class Keywords
{
    private array $keywords = [];
    private array $keywordsOnCard = [];

    /**
     * @param CardDefinition[] $cards
     */
    public static function fromCards(array $cards): self
    {
        $result = new self();

        foreach ($cards as $card) {
            $result->processCard($card);
        }

        asort($result->keywords, SORT_NUMERIC);
        $result->keywords = array_reverse($result->keywords, true);

        ksort($result->keywordsOnCard, SORT_NUMERIC);
        $result->keywordsOnCard = array_reverse($result->keywordsOnCard, true);

        return $result;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function getKeywordsOnCard(): array
    {
        return $this->keywordsOnCard;
    }

    private function processCard(CardDefinition $card)
    {
        foreach ($card->getKeywords() as $keyword) {
            if (isset($this->keywords[$keyword])) {
                $this->keywords[$keyword]++;
            } else {
                $this->keywords[$keyword] = 1;
            }
        }

        $keywordsCount = count($card->getSubTypes());
        if (!isset($this->keywordsOnCard[$keywordsCount])) $this->keywordsOnCard[$keywordsCount] = 0;
        $this->keywordsOnCard[$keywordsCount]++;
    }
}
