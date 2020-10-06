<?php

abstract class MysqlRepository
{
    // Docs: https://www.php.net/manual/ru/mysqli.prepare.php

    protected $dbConfig;

    protected $connection;

    public function __construct(DbConfig $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    protected function connect()
    {
        $h = $this->dbConfig->getHost();
        $u = $this->dbConfig->getUsername();
        $p = $this->dbConfig->getPassword();
        $d = $this->dbConfig->getDatabase();

        $this->connection = mysqli_connect($h, $u, $p, $d);

        if ($this->connection === false) {
            die("Can't connect to mysql database");
        }
    }

    protected function insert(string $tableName, array $values): int
    {
        $this->connect();

        $fieldNames = array_keys($values);
        $fieldValues = array_values($values);

        $fieldNamesInQuery = join(', ', $fieldNames);
        $fieldValuesInQuery = join(', ', array_map(function () {
            return '?';
        }, $fieldValues));

        $preparedQuery = "INSERT INTO `${tableName}` ( ${fieldNamesInQuery} ) VALUES ( ${fieldValuesInQuery} ) ; ";
        $result = $this->queryPrepared($preparedQuery, $fieldValues);
        $id = mysqli_insert_id($this->connection);

        $this->closeConnection();

        return $id;
    }

    protected function update(string $tableName, array $values): int
    {
        $this->connect();

        $id = $values['id'];
        unset($values['id']);

        $fieldNames = array_keys($values);
        $fieldValues = array_values($values);

        $fieldNames = array_map(function ($key) {
            return "`${key}`=?";
        }, $fieldNames);
        $fieldNames = join(", ", $fieldNames);

        $preparedQuery = "UPDATE `${tableName}` SET ${fieldNames} WHERE id=${id}";
        $result = $this->queryPrepared($preparedQuery, $fieldValues);
        $id = mysqli_insert_id($this->connection);

        $this->closeConnection();

        return $id;
    }

    protected function select(string $tableName, $fieldName = null, $fieldValue = null, $limit = null)
    {
        $this->connect();

        $fieldName = mysqli_real_escape_string($this->connection, $fieldName);
        $where = $fieldName ? "WHERE `${fieldName}`=?" : '';
        $limit = $limit ? 'LIMIT '.$limit : '';

        $preparedQuery = "SELECT * FROM `${tableName}` ${where} ORDER BY id DESC ${limit}";

        $args = $fieldValue ? [$fieldValue] : [];
        $result = $this->queryPrepared($preparedQuery, $args);

        $resultArray = [];
        for ($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
            $resultArray[$i] = $row;
        }

        $this->closeConnection();

        return $resultArray;
    }

    protected function closeConnection()
    {
        $this->connection->close();
    }

    protected function queryPrepared($query, array $args)
    {
        $stmt = mysqli_prepare($this->connection, $query);

        $params = [];
        $types = array_reduce($args, function ($string, &$arg) use (&$params) {
            $params[] = &$arg;
            if (is_float($arg)) {
                $string .= 'd';
            } elseif (is_integer($arg)) {
                $string .= 'i';
            } elseif (is_string($arg)) {
                $string .= 's';
            } else {
                $string .= 'b';
            }

            return $string;
        }, '');

        if ($types) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        $result = $stmt->execute();

        if (! $result) {
            error_log(mysqli_error($this->connection));
        }

        if ($result) {
            $result = $stmt->get_result();
        }

        $stmt->close();

        return $result;
    }
}