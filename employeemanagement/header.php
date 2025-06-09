<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: green;
            color: white;
            height: 100px;
            padding: 0 50px;
        }

        .nav-links a {
            text-decoration: none;
            margin-right: 30px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header-nav">
        <h1>Employee System</h1>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="addEmployee.php">Add Employee</a>
        </div>
    </div>
