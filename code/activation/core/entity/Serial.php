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

    private $period;


    public function __construct($id, int $keyId, bool $isBanned, string $serial, int $period)
    {
        $this->id = $id;
        $this->keyId = $keyId;
        $this->isBanned = $isBanned;
        $this->serial = $serial;
        $this->period = $period;
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

    public function getPeriod(): int
    {
        return $this->period;
    }
}