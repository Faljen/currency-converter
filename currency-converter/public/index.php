<?php

require_once '../src/App.php';
require_once '../src/NbpApi.php';
require_once '../src/Database.php';
require_once '../src/CurrencyConverter.php';

$db = new Database('localhost', '24714_converter', '24714_converter', 'Currencyconverter69420');
$api = new NbpApi();
$converter = new CurrencyConverter($db);
$app = new App($api, $db, $converter);

$app->updateCurrencies();

?>

<form method="POST" action="convert.php">
    <input type="number" name="amount" placeholder="Amount" required>
    <input type="text" name="from" placeholder="From" required>
    <input type="text" name="to" placeholder="To" required>
    <button type="submit">Convert</button>
</form>

<?php
echo 'Recent conversions: <br>';
$app->displayConversions();
echo '<br>';

echo 'All currencies: <br> ';
$app->displayCurrencies();
echo '<br>';