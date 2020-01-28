<?php

require_once __DIR__.'/SerialActivationStatusEnum.php';

class SerialActivationOutputDTO
{
    public $encryptedPcHash;

    public $key;

    public $serial;

    public $userName;

    public $status;

    public function __construct(
        string $status,
        string $userName = "",
        string $serial = "",
        string $encryptedPcHash = "",
        string $publicKey = ""
    ) {
        $this->encryptedPcHash = $encryptedPcHash;
        $this->key = $publicKey;
        $this->status = $status;
        $this->userName = $userName;
        $this->serial = $serial;
    }

    public static function ofStatus($status)
    {
        return new SerialActivationOutputDTO($status);
    }

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getEncryptedPcHash(): string
    {
        return $this->encryptedPcHash;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}