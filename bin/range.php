#!/usr/bin/env php
<?php

use Usmonaliyev\PostgresPartitioner\Partition\RangeBy;

require_once __DIR__ . '/../vendor/autoload.php';

$table = arg('table', 1);
$column = arg('column', 2);

$rangeBy = new RangeBy($table, $column, 'YEAR');

$rangeBy->execute();
