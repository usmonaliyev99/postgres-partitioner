<?php

namespace Usmonaliyev\PostgresPartitioner\Partition;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Usmonaliyev\PostgresPartitioner\Exceptions\UnsupportedFormatException;

class RangeBy extends Partition
{
    /**
     * Start of range
     * @var DateTime
     */
    public DateTime $start;

    /**
     * End of range
     * @var DateTime
     */
    public DateTime $end;

    protected string $format;

    /**
     * @param string $table
     * @param string $column
     * @param string<MONTH|YEAR> $format
     * @throws Exception
     */
    public function __construct(string $table, string $column, string $format = 'MONTH')
    {
        parent::__construct($table, $column);

        $this->setFormat($format);
        $this->loadRange();
        $this->buildPartitions();
    }

    /**
     * @throws Exception
     */
    private function loadRange(): void
    {
        $min = $this->db->select("SELECT MIN($this->column) AS min FROM $this->table;");

        if (isset($min[0]['min'])) {
            $this->start = new DateTime($min[0]['min']);
        } else {
            $this->start = new DateTime('previous year');
        }

        $this->end = new DateTime('next year');

        info('Range of table is defined: ' . $this->start->format($this->format) . ' to ' . $this->end->format($this->format));
    }

    public function setFormat(string $format): void
    {
        $callback = match ($format) {
            'YEAR' => fn() => 'Y',
            'MONTH' => fn() => 'Y_m',
            default => fn() => throw new UnsupportedFormatException(),
        };

        $this->format = $callback();
    }

    public function formatByYear(): bool
    {
        return $this->format === 'Y';
    }

    /**
     * @throws Exception
     */
    protected function buildPartitions(): void
    {
        $parts = $this->getParts();

        $this->partitions = array_map(fn ($date) => new PartitionPart($this->table, $date), $parts);
    }

    /**
     * @throws Exception
     */
    private function getParts(): array
    {
        $interval = match ($this->format) {
            'Y' => 'P1Y',
            'Y_m' => 'P1M',
        };

        $parts = [];
        $period = new DatePeriod($this->start, new DateInterval($interval), $this->end);

        foreach ($period as $date) {
            $parts[] = $date->format($this->format);
        }

        if ($this->formatByYear()) {
            $parts[] = $this->end->format($this->format);
        }

        info('Partitions are defined: ' . count($parts));

        return $parts;
    }
}
