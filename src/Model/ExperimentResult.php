<?php


namespace App\Model;


class ExperimentResult
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

    public function __toString()
    {
        return $this->successCount . '/' . $this->passCount;
    }
}
