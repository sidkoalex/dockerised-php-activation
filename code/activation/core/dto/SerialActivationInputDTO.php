<?php

class SerialActivationInputDTO
{
    private $serial;

    private $pcHash;

    private $productName;

    public function __construct(string $serial, string $pcHash, string $productName)
    {
        $this->serial = $serial;
        $this->pcHash = $pcHash;
        $this->productName = $productName;
    }

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getPcHash(): string
    {
        return $this->pcHash;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }
}