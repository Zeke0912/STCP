<?php

    class Database{
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "mydatabase";
        public $connection; //variable to store the connection

        //function to connect to the database
        public function connect(){
            $this->connection = new mysqli($this->host,$this->username,$this->password,$this->dbname);

            if($this->connection->connect_error){
                die("Connection error" . $this->connection->connect_error);
            }
            return $this->connection;
        }
    }
?>