<?php

namespace Usmonaliyev\PostgresPartitioner\Swap;

use Usmonaliyev\PostgresPartitioner\Database\DB;

class Foreign
{
    private DB $db;

    public string $constraintName;

    public string $tableName;

    public string $columnName;

    public function __construct(DB $db, array $foreign)
    {
        $this->db = $db;

        $this->constraintName = $foreign['constraint_name'];
        $this->tableName = $foreign['table_name'];
        $this->columnName = $foreign['column_name'];
    }

    public function drop(): int|bool
    {
        $sql = "ALTER TABLE $this->tableName DROP CONSTRAINT IF EXISTS $this->constraintName;";

        return $this->db->run($sql);
    }

    public function create($table): int|bool
    {
        $sql = "ALTER TABLE $this->tableName ADD CONSTRAINT $this->constraintName FOREIGN KEY ($this->columnName) REFERENCES $table (id);";

        return $this->db->run($sql);
    }

    public function rename(string $prefix): bool|int
    {
        $newConstraintName = str_replace($this->tableName . '_', '', $this->constraintName);
        $newConstraintName = "{$this->tableName}_{$prefix}{$newConstraintName}";

        $sql = "ALTER TABLE $this->tableName RENAME CONSTRAINT $this->constraintName TO $newConstraintName;";

        return $this->db->run($sql);
    }
}
