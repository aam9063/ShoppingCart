<?php
namespace ShoppingCart\Config;

class DbConfig {
    private $dbHost = 'localhost';
    private $dbUsername = 'root';
    private $dbPassword = '';
    private $dbName = 'commerce';

    public function connect() {
        $conn = new \mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
        if($conn->connect_error){
            die("Unable to connect database: " . $conn->connect_error);
        }
        return $conn;
    }
}
