<?php

require_once __DIR__.'/../entity/UserSerial.php';

interface UserSerialRepository
{
    function save(UserSerial $entity): int;

    function findById(int $id);

    function findAll(): array;

    function findBySerialIdAndStatus(int $id, string $status);

    function countNotUsed(int $serial): int;
}