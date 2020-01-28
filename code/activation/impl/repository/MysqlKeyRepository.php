<?php

require_once __DIR__.'/MysqlRepository.php';
require_once __DIR__.'/../../core/repository/KeyRepository.php';

class MysqlKeyRepository extends MysqlRepository implements KeyRepository
{
    const TABLE_NAME = 'key';

    function save(Key $entity): int
    {
        $row = $entity->toDbRow();
        unset($row['id']);

        $id = $this->insert(self::TABLE_NAME, $row);

        return $id;
    }

    function findById(int $id): Key
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

    function findLast()
    {
        $result = $this->select(self::TABLE_NAME, null, null,1);
        $result = $this->mapEntities($result);

        return ! empty($result) ? $result[0] : null;
    }


    private function mapEntities(array $result): array
    {
        $result = array_map(function ($row) {
            return Key::fromDbRow($row);
        }, $result);

        return $result;
    }
}