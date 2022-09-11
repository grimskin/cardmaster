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
