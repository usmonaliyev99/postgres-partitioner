<?php

namespace Usmonaliyev\PostgresPartitioner\Partition;

class Column
{
    public string $name;

    public string $type;
    public ?int $maxLength;

    public bool $isNullable;

    public ?string $default;

    public int $position;

    public function __construct(array $column)
    {
        $this->name = $column['column_name'];
        $this->type = $column['data_type'];
        $this->maxLength = $column['max_length'];
        $this->isNullable = $column['is_nullable'] == 'YES';
        $this->default = $column['column_default'];
        $this->position = $column['ordinal_position'];
    }
}
