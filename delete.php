<?php 
    require_once "classes/student.php";
    $student = new Student();
    $student->deleteStudent($_GET['idno']);

    header("location: index.php");
?>