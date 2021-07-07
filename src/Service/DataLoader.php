<?php


namespace App\Service;


use App\Model\CardData;

class DataLoader
{
    private bool $loaded = false;
    private array $filenames = [];
    private array $cardData = [];

    public function loadSet(string $setName): array
    {
        $data = $this->loadJsonFromFile($this->setNameToFileName($setName));

        $cards = [];

        foreach ($data['cards'] as $cardDatum) {
            $cards[] = CardData::createFromDatum($cardDatum);
        }

        $data['cards'] = $cards;

        return $data;
    }

    public function loadDataFromFile(string $fileName, $lazy = true)
    {
        if ($lazy) {
            $this->filenames[] = $fileName;

            return;
        }

        $data = $this->loadJsonFromFile($fileName);

        foreach ($data['cards'] as $cardDatum) {
            $this->parseCardData($cardDatum);
        }
    }

    private function loadJsonFromFile(string $fileName): array
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../../' . $fileName), true);

        if (isset($data['data']['cards']) && !isset($data['cards'])) {
            $data = $data['data'];
        }

        return $data;
    }

    private function setNameToFileName(string $setName): string
    {
        return 'data/'.strtoupper($setName).'.json';
    }

    private function parseCardData($cardDatum)
    {
        $card = CardData::createFromDatum($cardDatum);
        // easiest way to get raw card data
        /**
        if ($card->getName() == 'Fabled Passage') {
            echo json_encode($cardDatum, JSON_UNESCAPED_UNICODE); die();
        }
        */

        $this->cardData[$card->getName()] = $card;

        if ($card->getFaceName()) {
            $this->cardData[$card->getFaceName()] = $card;
        }
    }

    private function lazyLoad()
    {
        if ($this->loaded) return;

        foreach ($this->filenames as $filename) {
            $this->loadDataFromFile($filename, false);
        }

        $this->loaded = true;
    }

    public function getDataByName($cardName): ?CardData
    {
        $this->lazyLoad();

        return $this->cardData[$cardName] ?? null;
    }

    public function getAllData(): array
    {
        $this->lazyLoad();

        return $this->cardData;
    }
}
