<?php

require_once __DIR__.'/../entity/Serial.php';

trait SerialMapping
{
    public static function fromDbRow(array $row)
    {
        return new Serial($row['id'], $row['key_id'], $row['is_banned'], $row['serial'], $row['period']);
    }

    public function toDbRow()
    {
        return [
            'id' => $this->getId(),
            'key_id' => $this->getKeyId(),
            'is_banned' => intval($this->isBanned()),
            'serial' => $this->getSerial(),
            'period' => $this->getPeriod(),
        ];
    }

    public abstract function getId();

    public abstract function getKeyId(): int;

    public abstract function isBanned(): bool;

    public abstract function getPeriod(): ingett;

    public abstract function getSerial(): string;
}