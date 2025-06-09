<?php

require_once("classes/employee.php");

$employee = new Employee();
$employee->deleteEmployee($_GET['idno']);

header('location:index.php');
?>