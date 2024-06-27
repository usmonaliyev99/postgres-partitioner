<?php

namespace Usmonaliyev\PostgresPartitioner\Partition;

use Usmonaliyev\PostgresPartitioner\Database\DB;
use Usmonaliyev\PostgresPartitioner\Swap\Swapper;

abstract class Partition
{
    protected DB $db;

    protected Swapper $swapper;

    /**
     * All columns of target table
     * @var array<Column>
     */
    public array $columns;

    /**
     * Target table name
     * @var string
     */
    public string $table;

    /**
     * Target column name
     * @var string
     */
    public string $column;

    protected string $partitionTableSql;

    /**
     * List of partitions
     * @var array<PartitionPart>
     */
    protected array $partitions;

    public function __construct(string $table, string $column)
    {
        $this->table = $table;
        $this->column = $column;

        $this->db = new DB();

        $this->swapper = new Swapper($this->db, $this->table);

        $this->loadColumns();
        $this->build();
    }

    private function loadColumns(): void
    {
        $structure = file_get_contents(__DIR__ . "/../../sql/structure.sql");
        $structure = str_replace('#TABLE', $this->table, $structure);

        $columns = $this->db->select($structure);

        $this->columns = array_map(fn($column) => new Column($column), $columns);

        info('Columns of table are loaded from database: ' . count($columns) . ' columns');
    }

    protected function build(): void
    {
        $definitions = array_map(
            function (Column $column) {
                $temp = $column->name . ' ' . $column->type;

                if ($column->maxLength) {
                    $temp .= "({$column->maxLength})";
                }
                if ($column->isNullable) {
                    $temp .= ' NOT NULL';
                }
                if ($column->default) {
                    $temp .= ' DEFAULT ' . $column->default;
                }
                return $temp;
            },
            $this->columns
        );

        $sql = "CREATE TABLE IF NOT EXISTS $this->table (" . implode(",", $definitions) . ") PARTITION BY RANGE ($this->column);";

        $sql = str_replace([
            'bigint(64)',
            'integer(32)',
        ], [
            'INT8',
            'INT4'
        ], $sql);

        $this->partitionTableSql = $sql;

        info('Sql of main partition table is generated!');
    }

    protected function createMainPartitionTable(): void
    {
        done('Main partition table is creating...');

        $this->db->run($this->partitionTableSql);

        $primaryKey = "ALTER TABLE $this->table ADD PRIMARY KEY (id, $this->column);";
        $primaryKey .= "CREATE INDEX {$this->table}_{$this->column}_index ON $this->table ({$this->column});";

        $this->db->run($primaryKey);
    }

    /**
     * Generate main partition table with all parts
     * @return void
     */
    public function execute(): void
    {
        $this->swapper->dropForeignKeys();
        $this->swapper->renameTargetTable();

        $this->createMainPartitionTable();

        $this->createPartitionTables();

        $this->swapper->createForeignKeys();
    }

    protected function createPartitionTables(): void
    {
        array_map(
            fn(PartitionPart $partition) => $this->db->run($partition->getSql()),
            $this->partitions
        );

        done("Other partition tables are creating...");
    }
}
