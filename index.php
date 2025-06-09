<?php 
    require_once "classes/student.php";

    $student = new Student();
    $students = $student->getAllStudent();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href='addStudent.php'>ADD</a>
    <!--2 points-->
    <table border="1">
        <tr>
            <th>IDNO</th>
            <th>LASTNAME</th>
            <th>FIRSTNAME</th>
            <th>YEAR</th>
            <th>COURSE</th>
            <th>ACTIONS</th>
        </tr>
        <tr>
            <!--fetch assoc meaning get student as array-->
            <?php  while($row = $students->fetch_assoc()) 
                echo "
                    <tr>
                    <td>{$row['idno']}</td>
                    <td>{$row['fname']}</td>
                    <td>{$row['lname']}</td>
                    <td>{$row['year_level']}</td>
                    <td>{$row['course']}</td>
                    <td>
                        <a href='delete.php?idno={$row['idno']}'>Delete</a>
                    </td>
                    </tr>
                ";
            ?>
        </tr>
    </table>
</body>
</html>