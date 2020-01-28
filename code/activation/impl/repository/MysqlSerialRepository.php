<?php

require_once __DIR__.'/MysqlRepository.php';
require_once __DIR__.'/../../core/repository/SerialRepository.php';

class MysqlSerialRepository extends MysqlRepository implements SerialRepository
{
    const TABLE_NAME = 'serial';

    function save(Serial $entity): int
    {
        $row = $entity->toDbRow();
        unset($row['id']);

        $id = $this->insert(self::TABLE_NAME, $row);

        return $id;
    }

    function findById(int $id)
    {
        $result = $this->select(self::TABLE_NAME, 'id', $id);
        $result = $this->mapEntities($result);

        return ! empty($result) ? $result[0] : null;
    }

    function findAll(): array
    {
        $result = $this->select(self::TABLE_NAME);
        $result = $this->mapEntities($result);

        return $result;
    }

    function countKeyUse(int $keyId): int
    {
        $this->connect();

        $preparedQuery = "SELECT COUNT(*) FROM `".self::TABLE_NAME."` WHERE `key_id`=?;";
        $result = $this->queryPrepared($preparedQuery, [$keyId]);
        $result = mysqli_fetch_row($result);

        $this->closeConnection();
        return empty($result) ? 0 : $result[0];
    }

    function findBySerialNo(string $serial)
    {
        $result = $this->select(self::TABLE_NAME, 'serial', $serial);
        $result = $this->mapEntities($result);

        return ! empty($result) ? $result[0] : null;
    }

    private function mapEntities(array $result): array
    {
        $result = array_map(function ($row) {
            return Serial::fromDbRow($row);
        }, $result);

        return $result;
    }
}