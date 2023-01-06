<?php

class Database{

    private $pdo;

    public function __construct() 
    {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "database.php";

        $this->pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    public function query($query, $params = false) {
        if($params) {
            $request = $this->pdo->prepare($query);
            $request->execute($params);    
        }
        else {
            $request = $this->pdo->query($query);
        }
        return $request;
    }

    public function fetcher($query, $params) {
        $request = $this->pdo->prepare($query);
        $request->execute($params);
        return($request->fetch(PDO::FETCH_COLUMN, 0));
    }

    public function lastInsertedId() {
        return $this->pdo->lastInsertId();
    }

}