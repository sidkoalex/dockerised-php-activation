<?php

require_once __DIR__.'/../mapper/SerialMapping.php';
require_once __DIR__.'/SerialStatusEnum.php';

class Serial
{
    use SerialMapping;

    private $id;

    private $keyId;

    private $isBanned;

    private $serial;

    private $expireDate;

    public function __construct($id, int $keyId, bool $isBanned, string $serial, string $expireDate)
    {
        $this->id = $id;
        $this->keyId = $keyId;
        $this->isBanned = $isBanned;
        $this->serial = $serial;
        $this->expireDate = $expireDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKeyId(): int
    {
        return $this->keyId;
    }

    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getExpireDate(): string
    {
        return $this->expireDate;
    }
}