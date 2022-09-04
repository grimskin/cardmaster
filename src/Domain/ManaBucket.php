<?php


namespace App\Domain;


class ManaBucket
{
    private int $generic = 0;
    private int $colorless = 0;
    private int $white = 0;
    private int $blue = 0;
    private int $black = 0;
    private int $red = 0;
    private int $green = 0;

    public function put(string $color, int $amount = 1): void
    {

    }
    public function putWhite(int $amount = 1): void
    {
        $this->white += $amount;
    }
    public function putBlue(int $amount = 1): void
    {
        $this->blue += $amount;
    }
    public function putBlack(int $amount = 1): void
    {
        $this->black += $amount;
    }
    public function putRed(int $amount = 1): void
    {
        $this->red += $amount;
    }
    public function putGreen(int $amount = 1): void
    {
        $this->green += $amount;
    }
    public function putColorless(int $amount = 1): void
    {
        $this->colorless += $amount;
    }
    public function putGeneric(int $amount = 1): void
    {
        $this->generic += $amount;
    }

    public function contains(ManaBucket $bucket): bool
    {
        if ($this->generic < $bucket->generic) return false;
        if ($this->colorless < $bucket->colorless) return false;
        if ($this->white < $bucket->white) return false;
        if ($this->blue < $bucket->blue) return false;
        if ($this->black < $bucket->black) return false;
        if ($this->red < $bucket->red) return false;
        if ($this->green < $bucket->green) return false;

        return true;
    }

    public function copy(): ManaBucket
    {
        $result = new ManaBucket();
        $result->putColorless($this->colorless);
        $result->putGeneric($this->generic);
        $result->putWhite($this->white);
        $result->putBlue($this->blue);
        $result->putBlack($this->black);
        $result->putRed($this->red);
        $result->putGreen($this->green);

        return $result;
    }

    public function absorb(ManaBucket $bucket): void
    {
        $this->putColorless($bucket->colorless);
        $this->putGeneric($bucket->generic);
        $this->putWhite($bucket->white);
        $this->putBlue($bucket->blue);
        $this->putBlack($bucket->black);
        $this->putRed($bucket->red);
        $this->putGreen($bucket->green);
    }

    public function toString(): string
    {
        return $this->generic
            .'.'. $this->colorless
            .'.'. $this->white
            .'.'. $this->blue
            .'.'. $this->black
            .'.'. $this->red
            .'.'. $this->green;
    }
}
