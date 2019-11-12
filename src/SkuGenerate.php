<?php

namespace Gtd\Sku;

class SkuGenerate
{
    protected $position = [];

    protected $payload = [];

    public function __construct(array $position,array $payload)
    {
        $this->position = $position;
        $this->payload = $payload;
    }

    public static function make(array $position,array $payload): self
    {
        return new static($position, $payload);
    }

    public function setPosition(array $position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    public function getPayLoad()
    {
        return $this->payload;
    }
}