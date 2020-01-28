<?php

require_once __DIR__.'/../entity/Serial.php';

trait UserSerialMapping
{
    public static function fromDbRow(array $row)
    {
        return new UserSerial($row['id'], $row['user_id'], $row['user_name'], $row['serial_id'], $row['pc_hash'],
            $row['status']);
    }

    public function toDbRow()
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'user_name' => $this->getUserName(),
            'serial_id' => $this->getSerialId(),
            'pc_hash' => $this->getPcHash(),
            'status' => $this->getStatus(),
        ];
    }

    public abstract function getId();

    public abstract function getUserId(): int;

    public abstract function getUserName(): string;

    public abstract function getSerialId(): int;

    public abstract function getPcHash(): string;

    public abstract function getStatus(): string;
}