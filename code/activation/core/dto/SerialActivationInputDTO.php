<?php

class SerialActivationInputDTO
{
    private $serial;

    private $pcHash;

    public function __construct(string $serial, string $pcHash)
    {
        $this->serial = $serial;
        $this->pcHash = $pcHash;
    }

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getPcHash(): string
    {
        return $this->pcHash;
    }
}