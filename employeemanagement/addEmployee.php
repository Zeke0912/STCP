<?php

require_once("classes/employee.php");

$employee = new Employee();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee->addEmployee($_POST['idno'],
                            $_POST['fname'],
                            $_POST['lname'],
                            $_POST['course'],
                            $_POST['year'],
                            $_POST['phone']);
                            header('location:index.php');
}
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
    <form action="" method="POST">
        <button type="button" onclick="history.back()">Back</button>

        <label> IDNO </label>
        <input type="number" name ="idno">
        <br>
        <label> firstname </label>
        <input type="text" name="fname">
        <br>
        <label> lastname </label>
        <input type="text" name="lname">
        <br>
        <label> course </label>
        <select name="course"> 
            <option value="1"> 1 </option>
            <option value="2"> 2 </option>

        </select>
        <br>
        <label> year </label>
        <select name="year">
            <option value="bsit">BSIT </option>
            <option value="bscs">BSCS </option>
</select>
<br>
<label> phone </label>
<input type="number" name="phone">
<br>

<button type="submit" name="add"> Add Student </button>


</form>
    
</body>
</html>