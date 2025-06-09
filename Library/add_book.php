<?php include 'config.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];
    $published_year = $_POST['published_year'];

    $stmt = $conn->prepare("INSERT INTO Books (title, author, isbn, quantity, published_year) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $author, $isbn, $quantity, $published_year);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Book added successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: view_books.php");
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Add New Book</h1>
    <?php show_message(); ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" id="author" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" name="isbn" id="isbn" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="0" required>
                </div>
                
                <div class="mb-3">
                    <label for="published_year" class="form-label">Published Year</label>
                    <input type="number" name="published_year" id="published_year" class="form-control" 
                           min="1900" max="<?php echo date('Y'); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Book</button>
                <a href="view_books.php" class="btn btn-secondary">Back to Books</a>
            </form>
        </div>
    </div>
</body>
</html> 