<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/NbpApi.php';

class CurrencyConverter
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function convert($from, $to, $amount)
    {
        $fromMid = $this->database->getCurrencyMid($from);
        $toMid = $this->database->getCurrencyMid($to);
        if (!$fromMid || !$toMid) {
            throw new Exception("Currency not found in the database");
        }
        $convertedAmount = ($amount * $fromMid) / $toMid;
        return round($convertedAmount, 2);
    }
}