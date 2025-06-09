<?php

class Database{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $database = "employee";
    public $connection;

    public function connect(){
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->database);

        if($this->connection->connect_error){
            die("Connection failed: " . $this->connection->connect_error);
        }
        return $this->connection;
    }
}



?>