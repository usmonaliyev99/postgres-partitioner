<?php

namespace Usmonaliyev\PostgresPartitioner\Database;

use PDO;

class DB
{
    public PDO $pdo;

    public function __construct()
    {
        $host = config('DB_HOST');
        $port = config('DB_PORT');
        $dbname = config('DB_NAME');
        $user = config('DB_USER');
        $password = config('DB_PASSWORD');

        $this->pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;", $user, $password);
    }

    public function select(string $statement): array|false
    {
        $query = $this->pdo->query($statement);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function run(string $statement): bool|int
    {
        return $this->pdo->exec($statement);
    }
}
