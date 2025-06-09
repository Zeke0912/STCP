<?php

require_once("classes/database.php");

class Employee {
    private $connection;
    public function __construct(){
       

        $database = new Database();
        $this->connection = $database->connect();
    }


    //crud

    public function getAllEmployee(){
        return $this->connection->query("SELECT * FROM employee");
    }
    public function getByID($idno){
        return $this->connection->query("SELECT * FROM employee WHERE idno = '$idno'");
    }
    public function addEmployee($idno, $fname, $lname,$course, $year, $phone){
        $stmt = $this->connection->prepare("INSERT INTO employee (idno, fname, lname,course,year_level,phone)VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("issssi", $idno, $fname, $lname, $course, $year, $phone);
        return $stmt->execute();
    }
    public function updateEmployee($idno, $fname, $lname, $course, $year, $phone){
        $stmt = $this->connection->prepare("UPDATE employee SET fname = ? , lname = ?, course = ? , year_level = ?, phone= ? WHERE idno = '$idno'");
        $stmt->bind_param("ssssi", $fname , $lname , $course , $year, $phone);
        return $stmt->execute();
    }
    public function deleteEmployee($idno){
        return $this->connection->query("DELETE FROM employee WHERE idno = $idno");
    }
}


?>