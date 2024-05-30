<?php

namespace Usmonaliyev\PostgresPartitioner\Partition;

use Usmonaliyev\PostgresPartitioner\Database\DB;

abstract class Partition
{
    protected DB $db;

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

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;

        $this->db = new DB();

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

    protected function createMainPartitionTable(): bool|int
    {
        done('Main partition table is creating...');

        return $this->db->run($this->partitionTableSql);
    }

    protected function suffix(): string
    {
        return 'partitions';
    }
}
