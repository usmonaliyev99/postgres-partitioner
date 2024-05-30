#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$table = arg('table', 1);
$column = arg('column', 2);

$rangeBy = new \Usmonaliyev\PostgresPartitioner\Partition\RangeBy($table, $column, 'YEAR');
