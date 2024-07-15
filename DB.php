<?php

declare(strict_types=1);
require 'Currency.php';

class DB
{
    private PDO $pdo;
    public $currency;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=currency_converter', 'umarov', '2505');
        $this->currency = new Currency();
    }

    public function stateName(int $userCharId, string $state)
    {
        $query = "INSERT INTO savestate (userchatid, state, data) VALUES (:userchatid, :state, :data)";
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userchatid', $userCharId);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':data', $now);
        $stmt->execute();
    }

    public function sendStateName(int $userChatId, float|int $amount)
    {
        $query = "SELECT state FROM savestate WHERE userchatid = :userchatid ORDER BY data DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userchatid', $userChatId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$result) {
            return "Error: No conversion data found";
        }
        
        $state = $result['state'];
        $to_converter = explode(":", $state);
    
        if (count($to_converter) < 2) {
            return "Error: Invalid state format";
        }
    
        $response = $this->currency->converter($to_converter[1], $amount);
    
        return number_format($response, 2, ',', '.');
    }
    

    public function sendStateNameUser (int $userChatId) {
        $query = "SELECT state FROM savestate WHERE userchatid = :userchatid ORDER BY data DESC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userchatid', $userChatId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $state = $result['state'];

        return $state;
    }

    public function allUsersInfo (int $chatId, string $state, float|int $amount) {
        $query = "INSERT INTO usersinfo (chatId, state, amount, data) VALUE (:chatId, :state, :amount, :data)";
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':chatId', $chatId);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':data', $now);
        $stmt->execute();
    }

    public function users () {
        $query = "SELECT * FROM usersinfo";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

}
