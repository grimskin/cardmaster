<?php


namespace App\Helper;


class ResultsFileWriter
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function save(string $name, string $data): void
    {
        $fp = fopen($this->projectDir . '/results/' . $name . '.json', 'w+');

        fputs($fp, $data);

        fclose($fp);
    }
}
