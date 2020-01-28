<?php

require_once __DIR__.'/../entity/Serial.php';

interface SerialRepository
{
    function save(Serial $entity): int;

    function findById(int $id);

    function findBySerialNo(string $serial);

    function findAll(): array;

    function countKeyUse(int $keyId): int;
}