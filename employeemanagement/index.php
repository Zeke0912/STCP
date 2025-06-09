<?php
include 'header.php';
require_once("classes/employee.php");



$employee = new Employee();
$employees = $employee->getAllEmployee();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a href='addEmployee.php'> ADD </a>
    
<table border ="1">
    <tr>
    <td> IDNO </td>
    <td> Firstname </td>
    <td> Lastname </td>
    <td> Course </td>
    <td> Year </td>
    <td> Phone </td>
    <td> Actions </td>

</tr>
<?php while($row = $employees->fetch_assoc())
    echo "
        <tr>
        <td>{$row['idno']}</td>
        <td>{$row['fname']}</td>
        <td>{$row['lname']}</td>
        <td>{$row['course']}</td>
        <td>{$row['year_level']}</td>
        <td>{$row['phone']}</td>
        <td>
        <a href='editEmployee.php?idno={$row['idno']}'> EDIT </a>
        <a href='deleteEmployee.php?idno={$row['idno']}'> DELETE </a>
        </td>

        </tr>
    "
 ?>

</table>


    
</body>
</html>
