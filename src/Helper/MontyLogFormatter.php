<?php


namespace App\Helper;


use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class MontyLogFormatter implements FormatterInterface
{
    public function format(LogRecord $record): string
    {
        return '['.$record->datetime->format('Y-m-d H:i:s').'][' . $record->level->getName() . '] '
            . $record->message . PHP_EOL;
    }

    public function formatBatch(array $records): array
    {
        foreach ($records as $key => $record) {
            $records[$key] = $this->format($record);
        }

        return $records;
    }
}
