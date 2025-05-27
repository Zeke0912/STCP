<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Library Books</h1>
        <a href="add_book.php" class="btn btn-success">Add New Book</a>
    </div>

    <?php show_message(); ?>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Quantity</th>
                        <th>Published Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM Books ORDER BY title");
                    if (!$result) {
                        echo "<tr><td colspan='7' class='text-center text-danger'>Error: " . $conn->error . "</td></tr>";
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['book_id']}</td>
                                    <td>{$row['title']}</td>
                                    <td>{$row['author']}</td>
                                    <td>{$row['isbn']}</td>
                                    <td>{$row['quantity']}</td>
                                    <td>{$row['published_year']}</td>
                                    <td>
                                        <a href='edit_book.php?id={$row['book_id']}' class='btn btn-sm btn-warning'>Edit</a>
                                        <a href='delete_book.php?id={$row['book_id']}' class='btn btn-sm btn-danger' 
                                           onclick='return confirm(\"Are you sure you want to delete this book?\");'>Delete</a>
                                    </td>
                                </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <a href="view_borrowers.php" class="btn btn-info">View Borrowers</a>
        <a href="view_loans.php" class="btn btn-primary">View Loans</a>
    </div>
</body>
</html> 