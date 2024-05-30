<?php

namespace Usmonaliyev\PostgresPartitioner\Partition;

class PartitionPart
{
    protected string $table;

    protected string $suffix;

    protected string $date;

    protected string $sql;

    public function __toString(): string
    {
        return $this->sql;
    }

    public function __construct(string $table, string $suffix)
    {
        $this->table = $table;
        $this->suffix = $suffix;

        $this->date = str_replace('_', '-', $suffix);

        $this->build();
    }

    public function isYear(): bool
    {
        return strlen($this->date) == 4;
    }

    public function isMonth(): bool
    {
        return strlen($this->date) == 7;
    }

    protected function build(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table}_{$this->suffix} PARTITION OF $this->table";

        if ($this->isYear()) {
            $nextYear = $this->date + 1;

            $sql .= " FOR VALUES FROM ('$this->date-01-01') TO ('$nextYear-01-01');";
        }
        if ($this->isMonth()) {
            $lastDay = date('Y-m-01', strtotime($this->date . ' next month'));

            $sql .= " FOR VALUES FROM ('$this->date-01') TO ('$lastDay');";
        }

        $this->sql = $sql;
    }

    public function getSql(): string
    {
        return $this->sql;
    }
}
