<?php 
    require_once "classes/database.php";

    class Student{

        private $connection;

        public function __construct()
        {
            $database = new Database();
            $this->connection = $database->connect();
        }

        //CRUD

        public function getAllStudent(){
            return $this->connection->query("SELECT * FROM students");
        }

        public function getByID($idno){
            return $this->connection->query("SELECT * FROM students WHERE idno = $idno");
        }

        public function addStudent($idno, $fname, $lname, $year_level, $course){
            $stmt = $this->connection->prepare("INSERT INTO students(idno,fname,lname,year_level,course) VALUES(?,?,?,?,?)");
            $stmt->bind_param("issis",$idno, $fname, $lname, $year_level, $course);
            return $stmt->execute();
        }

        public function updateStudent($idno, $fname, $lname, $year_level, $course){
            $stmt = $this->connection->prepare("UPDATE students SET fname = ?, lname = ?, year_level = ?, course = ? WHERE idno = $idno");
            $stmt->bind_param("ssis", $lastname, $firstname, $year_level, $course);
            return $stmt->execute();
        }

        public function deleteStudent($idno){
            return $this->connection->query("DELETE FROM students WHERE idno = $idno");
        }
    }
?>