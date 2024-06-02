<?php

namespace Usmonaliyev\PostgresPartitioner\Swap;

use PDO;
use Usmonaliyev\PostgresPartitioner\Database\DB;

class Swapper
{
    private DB $db;

    /**
     * Name of target table
     * @var string
     */
    protected string $table;

    /**
     * All foreign keys belongs to target table
     * @var array<Foreign>
     */
    public array $foreignKeys;

    public string $prefix = 'temp_';

    public function __construct(DB $db, string $table)
    {
        $this->table = $table;

        $this->db = $db;

        $this->loadForeignKeys();
    }

    protected function loadForeignKeys(): void
    {
        $sql = file_get_contents(__DIR__ . '/../../sql/foreign-keys.sql');
        $sql = str_replace('#TABLE', $this->table, $sql);

        $foreignKeys = $this->db->select($sql);
        $this->foreignKeys = array_map(fn($foreign) => new Foreign($this->db, $foreign), $foreignKeys);

        info("All foreign keys loaded: " . count($foreignKeys));
    }

    /**
     * Drops all foreign keys
     * @return void
     */
    public function dropForeignKeys(): void
    {
        array_map(fn(Foreign $foreign) => $foreign->drop(), $this->foreignKeys);

        info('Foreign keys are dropped.');
    }

    /**
     * Create foreign keys which are dropped
     * @return void
     */
    public function createForeignKeys(): void
    {
        array_map(fn(Foreign $foreign) => $foreign->create($this->table), $this->foreignKeys);

        info('Foreign keys are created.');
    }

    /**
     * Rename target table
     * @return void
     */
    public function renameTargetTable(): void
    {
        $sql = "ALTER TABLE $this->table RENAME TO {$this->prefix}{$this->table};";

        $this->db->run($sql);

        info('Target table is renamed: ' . $this->table . ' => ' . $this->prefix . $this->table);
    }
}
