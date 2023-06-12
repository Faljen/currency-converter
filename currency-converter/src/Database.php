<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct($host, $db_name, $username, $password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
    }

    public function dbConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    public function clearCurrencies()
    {
        $this->conn = $this->dbConnection();
        $query = "DELETE FROM currencies";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    public function saveCurrencies($currencies)
    {
        $this->conn = $this->dbConnection();
        $query = "INSERT INTO currencies (currency, code, mid) VALUES (:currency, :code, :mid) ON DUPLICATE KEY UPDATE currency = :currency, mid = :mid";
        $stmt = $this->conn->prepare($query);

        foreach ($currencies as $currency) {
            $stmt->bindParam(':currency', $currency['currency']);
            $stmt->bindParam(':code', $currency['code']);
            $stmt->bindParam(':mid', $currency['mid']);
            $stmt->execute();
        }
    }

    public function getCurrencies()
    {
        $this->conn = $this->dbConnection();
        $query = "SELECT * FROM currencies";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrencyMid($code)
    {
        $this->conn = $this->dbConnection();
        $query = "SELECT mid FROM currencies WHERE code = :code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['mid'];
        } else {
            throw new Exception("Currency not found in the database: $code");
        }
    }

    public function saveConversion($from, $to, $amount, $convertedAmount)
    {
        $this->conn = $this->dbConnection();
        $query = "INSERT INTO conversions (from_currency, to_currency, amount, converted_amount) VALUES (:from_currency, :to_currency, :amount, :converted_amount)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':from_currency', $from);
        $stmt->bindParam(':to_currency', $to);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':converted_amount', $convertedAmount);
        $stmt->execute();
    }

    public function getConversions()
    {
        $this->conn = $this->dbConnection();
        $query = "SELECT * FROM conversions";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}