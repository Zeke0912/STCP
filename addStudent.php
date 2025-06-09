<?php 
    require_once "classes/Student.php";

    $student = new Student();

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $student->addStudent($_POST['idno'],
                            $_POST['fname'],
                            $_POST['lname'],
                            $_POST['year_level'],
                            $_POST['course']);
                            header("location: index.php");
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="" method="POST">
        <label for="idno">Enter idno: </label>
        <input type="number" name="idno"> <br>

        <label for="idno">Enter Firstname: </label>
        <input type="text" name="fname"> <br>

        <label for="idno">Enter Lastname: </label>
        <input type="text" name="lname"> <br>
        
        <label for="idno">year level: </label>
        <select name="year_level" id="year_level">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select><br>
        <label for="idno">course: </label>
        <select name="course" id="course">
            <option value="bsit">bsit</option>
            <option value="bscs">bscs</option>
        </select><br>
        <button type="submit">ADD</button>
    </form>
</body>
</html>