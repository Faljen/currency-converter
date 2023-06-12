<?php

require_once '../src/App.php';
require_once '../src/NbpApi.php';
require_once '../src/Database.php';
require_once '../src/CurrencyConverter.php';

$amount = isset($_POST['amount']) && is_numeric($_POST['amount']) ? $_POST['amount'] : null;
$from = isset($_POST['from']) ? strtoupper(trim($_POST['from'])) : null;
$to = isset($_POST['to']) ? strtoupper(trim($_POST['to'])) : null;

if ($amount && $from && $to) {
    $db = new Database('localhost', '24714_converter', '24714_converter', 'Currencyconverter69420');
    $api = new NbpApi();
    $converter = new CurrencyConverter($db);
    $app = new App($api, $db, $converter);
    $app->convertCurrency($from, $to, $amount);
} else {
    echo "Invalid data!";
}
echo '<div style="margin-bottom: 50px"></div>'
    ?>

<a href="index.php"
    style=" padding: 5px; text-decoration: none; background-color: #007BFF; color: white; border-radius: 5px;">Back</a>