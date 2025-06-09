<?php 
    require_once "classes/Student.php";

    $student = new Student();
    $data = $student->getByID($_GET['idno'])->fetch_assoc();

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $student->addStudent($_POST['idno'],
                            $_POST['lastname'],
                            $_POST['firstname'],
                            $_POST['year_level'],
                            $_POST['course']);
                            header("location: index.php");
    }
?>


<body>
    <h2>Edit Student</h2>
    <form action="" method="POST">
        <label for="idno">Enter idno: </label>
        <input type="number" value="<?= htmlspecialchars($data[`idno`])?>" name="idno" readonly> <br>

        <label for="idno">Enter lastname: </label>
        <input type="text" value="<?= htmlspecialchars($data[`lastname`])?>" name="lastname"> <br>

        <label for="idno">Enter firstname: </label>
        <input type="text" value="<?= htmlspecialchars($data[`firstname`])?>" name="firstname"> <br>
        
        <label for="idno">year level: </label>
        <select name="year_level" id="year_level">
            <option value="1" <?= $data['year_level'] == '1' ? 'selected' : '' ?>>1</option>
            <option value="2" <?= $data['year_level'] == '2' ? 'selected' : '' ?>>2</option>
            <option value="3" <?= $data['year_level'] == '3' ? 'selected' : '' ?>>3</option>
            <option value="4" <?= $data['year_level'] == '4' ? 'selected' : '' ?>>4</option>
        </select><br>

        <label for="idno">course: </label>
        <select name="course" id="course">
            <option value="bsit" <?= $data['course'] == 'bsit' ? 'selected' : '' ?>>bsit</option>
            <option value="bscs" <?= $data['course'] == 'bscs' ? 'selected' : '' ?>>bscs</option>
        </select><br>

        <button type="submit">ADD</button>
    </form>
</body>
