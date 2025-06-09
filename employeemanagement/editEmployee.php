<?php

require_once("classes/employee.php");

$employee = new Employee();
$data = $employee->getByID($_GET['idno'])->fetch_assoc();


if($_SERVER["REQUEST_METHOD"] == "POST"){
$employee->updateEmployee($_POST['idno'],
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
    <label for="idno">Enter idno: </label>
    <input type="number" value="<?= htmlspecialchars($data['idno'])?>" name= "idno" readonly> <br>

    <label for="fname"> Enter Firstname: </label>
    <input type="text" value="<?= htmlspecialchars($data['fname'])?>" name="fname">|
    <br>
    <label for="lname"> Enter Lastname: </label>
    <input type="text" value="<?= htmlspecialchars($data['lname'])?>" name="lname">
    <br>
    <label for="course"> Enter Course: </label>
    <select name="course"> 
    <option value="1"> 1st </option>
    <option value="2"> 2nd </option>
    <option value="3"> 3rd </option>
    <option value= "4"> 4th </option>

    </select>
    <label for="year"> Enter Year: </label>
    <select name="year"> 
    <option value="bsit"> BSIT </option>
    <option value="bscs"> BSCS </option>
</select>
<br>

    <label for="phone">Enter phone: </label>
    <input type="text" value="<?= htmlspecialchars($data['phone'])?>" name="phone">|
    <br>

</select>
<button type="submit" name="edit"> Edit </button>
</form>



    
</body>
</html>