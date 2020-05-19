<?php


namespace App\Model;


use JsonSerializable;

class ExperimentResult implements JsonSerializable
{
    private $passCount = 0;
    private $successCount = 0;

    public function tickPassCount()
    {
        ++$this->passCount;
    }

    public function tickSuccessCount()
    {
        ++$this->successCount;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getPassCount()
    {
        return $this->passCount;
    }

    public function __toString()
    {
        return $this->successCount . '/' . $this->passCount;
    }

    public function jsonSerialize()
    {
        return [
            'success' => $this->successCount,
            'total' => $this->passCount,
        ];
    }
}
