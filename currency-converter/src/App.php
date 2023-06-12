<?php
class App
{
    private $nbpApi;
    private $database;
    private $converter;

    public function __construct($nbpApi, $database, $converter)
    {
        $this->nbpApi = $nbpApi;
        $this->database = $database;
        $this->converter = $converter;
    }

    public function updateCurrencies()
    {
        $currencies = $this->nbpApi->getCurrencyData();

        // Dodajemy PLN do tablicy
        $currencies[] = array(
            'currency' => 'złoty polski',
            'code' => 'PLN',
            'mid' => 1.00
        );

        $this->database->saveCurrencies($currencies);
    }

    public function displayCurrencies()
    {
        $currencies = $this->database->getCurrencies();
        foreach ($currencies as $currency) {
            echo "" . $currency['currency'] . ", " . $currency['code'] . ", " . $currency['mid'] . "<br>";
        }
    }

    public function convertCurrency($from, $to, $amount)
    {
        try {
            $convertedAmount = $this->converter->convert($from, $to, $amount);
            $this->database->saveConversion($from, $to, $amount, $convertedAmount);
            echo "from: " . $from . ", to: " . $to . ", amount: " . $amount . ", converted amount: " . $convertedAmount . "<br>";
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "<br>";
        }
    }


    public function displayConversions()
    {
        $conversions = $this->database->getConversions();
        foreach ($conversions as $conversion) {
            echo "from: " . $conversion['from_currency'] . ", to: " . $conversion['to_currency'] . ", amount: " . $conversion['amount'] . ", converted amount: " . $conversion['converted_amount'] . "<br>";
        }
    }
}