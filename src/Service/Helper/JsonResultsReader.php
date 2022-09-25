<?php


namespace App\Service\Helper;


class JsonResultsReader
{
    private string $resultsDir;

    public function __construct(string $projectDir)
    {
        $this->resultsDir = $projectDir . '/results/';
    }

    public function getResults(string $fileName, array $keyTranslations = [], bool $flipKeys = false): array
    {
        $json = $this->readFile($fileName.'.json');
        $parsed = json_decode($json, true);

        $result = [];

        foreach ($parsed as $index1=>$set) {
            foreach ($set as $index2=>$experiment) {
                if ($flipKeys) {
                    $key1 = $keyTranslations[$index2] ?? $index2;
                    $key2 = $keyTranslations[$index1] ?? $index1;
                } else {
                    $key1 = $keyTranslations[$index1] ?? $index1;
                    $key2 = $keyTranslations[$index2] ?? $index2;
                }

                $result[$key1][$key2] = [
                    'success' => $experiment['success'],
                    'total' => $experiment['total'],
                ];
            }
        }

        return $result;
    }

    /**
     * @deprecated - switch to getDualResultsV2
     */
    public function getDualResults(string $filePattern): array
    {
        $results = [];

        for ($i=2; $i<=8; $i++) {
            $fileName = $i . $filePattern . '.json';
            if (!$this->fileExists($fileName)) continue;

            $json = $this->readFile($fileName);
            $parsed = json_decode($json, true);

            foreach ($parsed as $rowKey=>$row) {
                $inversed = str_contains($rowKey, 'B');

                foreach (['14', '16', '18'] as $deckKey) {
                    $results[$rowKey][$deckKey]['only'] = [
                        'success' => $row[$deckKey]['success'],
                        'total' => $row[$deckKey]['total'],
                    ];
                }
                foreach (['15g', '17g'] as $deckKey) {
                    $resDeckKey = $inversed ? str_replace('g', 'b', $deckKey) : $deckKey;
                    $deckKey = (string)intval($deckKey);
                    $results[$rowKey][$deckKey]['favored'] = [
                        'success' => $row[$resDeckKey]['success'],
                        'total' => $row[$resDeckKey]['total'],
                    ];
                }
                foreach (['15b', '17b'] as $deckKey) {
                    $resDeckKey = $inversed ? str_replace('b', 'g', $deckKey) : $deckKey;
                    $deckKey = (string)intval($deckKey);
                    $results[$rowKey][$deckKey]['handicap'] = [
                        'success' => $row[$resDeckKey]['success'],
                        'total' => $row[$resDeckKey]['total'],
                    ];
                }
                ksort($results[$rowKey]);
            }
        }

        $pivotedResult = [];
        foreach ($results as $rowKey=>$row) {
            foreach ($row as $colKey=>$value) {
                $pivotedResult[$colKey][$rowKey] = $value;
            }
        }

        return $pivotedResult;
    }

    public function getDualResultsV2(string $filePattern): array
    {
        $results = [];

        foreach (['2', '3', '3a', '3b', '4', '4a', '4b', '5', '5a', '5b', '6', '7', '8'] as $i) {
            $fileName = $i . $filePattern . '.json';
            if (!$this->fileExists($fileName)) continue;

            $json = $this->readFile($fileName);
            $parsed = json_decode($json, true);

            foreach ($parsed as $rowKey=>$row) {
                foreach (['14', '15', '16', '17', '18'] as $deckKey) {
                    if (!isset($row[$deckKey])) continue;

                    $results[$rowKey][$deckKey]['only'] = [
                        'success' => $row[$deckKey]['success'],
                        'total' => $row[$deckKey]['total'],
                    ];
                }
                foreach (['15a', '17a'] as $deckKey) {
                    if (!isset($row[$deckKey])) continue;
                    $resDeckKey = (string)intval(str_replace('a', '', $deckKey));
                    $results[$rowKey][$resDeckKey]['favored'] = [
                        'success' => $row[$deckKey]['success'],
                        'total' => $row[$deckKey]['total'],
                    ];
                }
                foreach (['15b', '17b'] as $deckKey) {
                    if (!isset($row[$deckKey])) continue;
                    $resDeckKey = (string)intval(str_replace('b', '', $deckKey));
                    $results[$rowKey][$resDeckKey]['handicap'] = [
                        'success' => $row[$deckKey]['success'],
                        'total' => $row[$deckKey]['total'],
                    ];

                    if ($results[$rowKey][$resDeckKey]['handicap']['success'] <= $results[$rowKey][$resDeckKey]['favored']['success']) continue;

                    $a = $results[$rowKey][$resDeckKey]['handicap'];
                    $results[$rowKey][$resDeckKey]['handicap'] = $results[$rowKey][$resDeckKey]['favored'];
                    $results[$rowKey][$resDeckKey]['favored'] = $a;
                }
                ksort($results[$rowKey]);
            }
        }

        $pivotedResult = [];
        foreach ($results as $rowKey=>$row) {
            foreach ($row as $colKey=>$value) {
                $pivotedResult[$colKey][$rowKey] = $value;
            }
        }

        return $pivotedResult;
    }

    public function fileExists(string $fileName): string
    {
        return file_exists($this->resultsDir . $fileName);
    }

    public function readFile(string $fileName): string
    {
        $result = '';

        $fp = fopen($this->resultsDir . $fileName, 'r');

        while (!feof($fp)) {
            $result .= fgets($fp);
        }

        fclose($fp);

        return $result;
    }
}
