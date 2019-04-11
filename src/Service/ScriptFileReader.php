<?php


namespace App\Service;


class ScriptFileReader
{
    private $experimentsDir;

    public function __construct(string $projectDir)
    {
        $this->experimentsDir = $projectDir . '/scripts/';
    }

    public function hasFile(string $fileName): bool
    {
        return file_exists($this->experimentsDir . $fileName);
    }

    public function readFile(string $fileName): array
    {
        $result = [];

        $fp = fopen($this->experimentsDir . $fileName, 'r');

        while (!feof($fp)) {
            $result[] = fgets($fp);
        }

        fclose($fp);

        return $result;
    }
}
