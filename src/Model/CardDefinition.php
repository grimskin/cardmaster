<?php

namespace App\Model;

class CardDefinition
{
    private $name = '';
    private $isStub = false;

    public function isStub(): bool
    {
        return $this->isStub;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function define(
        string $name,
        bool $isStub = false
    ): self {
        $result = new self();

        $result->name = $name;
        $result->isStub = $isStub;

        return $result;
    }
}
