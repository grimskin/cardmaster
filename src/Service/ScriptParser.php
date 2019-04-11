<?php


namespace App\Service;


class ScriptParser
{
    public function getCommand(string $row)
    {
        $data = explode(':', $row, 2);

        return strtolower($data[0]);
    }

    public function getData(string $row)
    {
        $data = explode(':', $row, 2);

        return $data[1];
    }

    public function getCardName(string $row)
    {
        $data = explode(':', $row, 2);

        return trim(substr(
            $data[1],
            strpos($data[1], '[') + 1,
            strpos($data[1], ']') - strpos($data[1], '[') - 1
        ));
    }

    public function getCardAmount(string $row)
    {
        $data = explode(',' , $row);

        return (int) $data[count($data) - 1];
    }

    public function getScenarioName(string $row)
    {
        $data = explode(':', $row, 2);

        return strtolower(trim($data[1]));
    }

    public function getConditionName(string $row)
    {
        $data = explode(':', $row, 2);

        return strtolower(trim($data[1]));
    }

    public function getPassCount(string $row)
    {
        $data = explode(':', $row, 2);

        return (int) $data[1];
    }
}
