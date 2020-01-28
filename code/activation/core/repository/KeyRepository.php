<?php

require_once __DIR__.'/../entity/Key.php';

interface KeyRepository
{
    function save(Key $entity): int;

    function findById(int $id): Key;

    function findAll(): array;

    function findLast();
}