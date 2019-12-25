<?php


namespace App\Service;


use App\Model\CardData;

class DataLoader
{
    private $cardData = [];

    public function loadDataFromFile(string $fileName)
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../../' . $fileName), true);

        foreach ($data['cards'] as $cardDatum) {
            $this->parseCardData($cardDatum);
        }
    }

    private function parseCardData($cardDatum)
    {
        $card = CardData::createFromDatum($cardDatum);

        $this->cardData[$card->getName()] = $card;
    }

    public function getDataByName($cardName): ?CardData
    {
        return $this->cardData[$cardName] ?? null;
    }

    public function getAllData(): array
    {
        return $this->cardData;
    }
}

/**
{
    "artist": "Eytan Zana",
    "borderColor": "black",
    "colorIdentity": [
        "G"
    ],
    "colors": [],
    "convertedManaCost": 0.0,
    "foreignData": [
    {
        "language": "German",
        "multiverseId": 460012,
        "name": "Wald",
        "type": "Standardland — Wald"
    }
    ],
    "frameVersion": "2015",
    "hasFoil": true,
    "hasNonFoil": true,
    "isStarter": true,
    "layout": "normal",
    "legalities": {
    "commander": "Legal",
    "duel": "Legal",
    "frontier": "Legal",
    "future": "Legal",
    "legacy": "Legal",
    "modern": "Legal",
    "pauper": "Legal",
    "penny": "Legal",
    "standard": "Legal",
    "vintage": "Legal"
    },
    "multiverseId": 459998,
    "name": "Forest",
    "number": "264",
    "originalText": "G",
    "originalType": "Basic Land — Forest",
    "printings": [
    "AKH",
    "BBD",
    "BFZ",
    "C13",
    "C14",
    "C15",
    "C16",
    "C17",
    "C18",
    "CM2",
    "CMA",
    "DDL",
    "DDM",
    "DDO",
    "DDP",
    "DDR",
    "DDS",
    "DDU",
    "DOM",
    "DTK",
    "E01",
    "EVG",
    "FRF",
    "G17",
    "GK1",
    "GK2",
    "GNT",
    "GRN",
    "GS1",
    "GVL",
    "HOU",
    "J14",
    "KLD",
    "KTK",
    "M14",
    "M15",
    "M19",
    "ORI",
    "PCA",
    "PF19",
    "PRM",
    "PRW2",
    "PRWK",
    "PSS2",
    "PSS3",
    "PZ2",
    "RIX",
    "RNA",
    "RTR",
    "SOI",
    "THS",
    "TPR",
    "UST",
    "XLN"
    ],
    "rarity": "common",
    "rulings": [],
    "scryfallId": "48764854-d268-462d-a016-27329c8f062d",
    "scryfallIllustrationId": "24132847-d398-4363-8d90-4fa63fc23c4d",
    "scryfallOracleId": "b34bb2dc-c1af-4d77-b0b3-a0fb342a5fc6",
    "subtypes": [
    "Forest"
    ],
    "supertypes": [
    "Basic"
    ],
    "tcgplayerProductId": 183584,
    "tcgplayerPurchaseUrl": "https://mtgjson.com/links/947333192c2f1646",
    "text": "({T}: Add {G}.)",
    "type": "Basic Land — Forest",
    "types": [
    "Land"
    ],
    "uuid": "92819a55-b811-5d37-a3a7-1e6843bd3514",
    "uuidV421": "4e138289-be7d-5fca-b571-acaf3438565f"
},



 */