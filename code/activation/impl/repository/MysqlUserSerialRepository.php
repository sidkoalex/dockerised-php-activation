<?php

require_once __DIR__.'/MysqlRepository.php';
require_once __DIR__.'/../../core/repository/UserSerialRepository.php';

class MysqlUserSerialRepository extends MysqlRepository implements UserSerialRepository
{
    const TABLE_NAME = 'user_serial';

    function save(UserSerial $entity): int
    {
        $row = $entity->toDbRow();
        if (empty($row['id'])) {
            unset($row['id']);
            $id = $this->insert(self::TABLE_NAME, $row);
        } else {
            $id = $this->update(self::TABLE_NAME, $row);
        }

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

        return $this->mapEntities($result);
    }

    function findBySerialIdAndStatus(int $id, string $status)
    {
        $result = $this->select(self::TABLE_NAME, 'serial_id', $id);
        $result = $this->mapEntities($result);

        $result = array_filter($result, function ($entity) use ($status) {
            return $entity->getStatus() == $status;
        });

        return ! empty($result) ? array_pop($result) : null;
    }

    function countNotUsed(int $serialId): int
    {
        $this->connect();

        $preparedQuery = "SELECT COUNT(*) FROM `".self::TABLE_NAME."` WHERE `serial_id`=? AND `status`=?;";
        $result = $this->queryPrepared($preparedQuery, [$serialId, SerialStatusEnum::NOT_USED]);
        $result = mysqli_fetch_row($result);

        $this->closeConnection();

        return empty($result) ? 0 : $result[0];
    }

    private function mapEntities(array $result): array
    {
        $result = array_map(function ($row) {
            return UserSerial::fromDbRow($row);
        }, $result);

        return $result;
    }
}