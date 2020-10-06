<?php

require_once __DIR__.'/SerialActivationStatusEnum.php';

class SerialActivationOutputDTO
{
    public $encryptedHash;

    public $key;

    public $serial;

    public $userName;

    public $status;

    public $serialPeriod;

    public $activatedAt;

    public function __construct(
        string $status,
        string $userName = "",
        string $serial = "",
        string $encryptedHash = "",
        string $publicKey = "",
        int    $serialPeriod = 0,
        string $activatedAt = ""
    ) {
        $this->encryptedHash = $encryptedHash;
        $this->key = $publicKey;
        $this->status = $status;
        $this->userName = $userName;
        $this->serial = $serial;
        $this->serialPeriod = $serialPeriod;
        $this->activatedAt=$activatedAt;
    }

    public static function ofStatus($status)
    {
        return new SerialActivationOutputDTO($status);
    }

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getEncryptedHash(): string
    {
        return $this->encryptedHash;
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

    public function getSerialPeriod()
    {
        return $this->serialPeriod;
    }

    public function getActivatedAt(): string
    {
        return $this->activatedAt;
    }
}