<?php
class NbpApi
{
    private $apiUrl = "http://api.nbp.pl/api/exchangerates/tables/A/?format=json";

    public function getCurrencyData()
    {
        $json = file_get_contents($this->apiUrl);
        return json_decode($json, true)[0]['rates'];
    }
}