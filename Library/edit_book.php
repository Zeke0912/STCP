<?php include 'config.php'; ?>

<?php
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "No book specified";
    $_SESSION['message_type'] = "danger";
    header("Location: view_books.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Books WHERE book_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    $_SESSION['message'] = "Book not found";
    $_SESSION['message_type'] = "danger";
    header("Location: view_books.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];
    $published_year = $_POST['published_year'];

    $stmt = $conn->prepare("UPDATE Books SET title=?, author=?, isbn=?, quantity=?, published_year=? WHERE book_id=?");
    $stmt->bind_param("sssisi", $title, $author, $isbn, $quantity, $published_year, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Book updated successfully!";
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
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Edit Book</h1>
    <?php show_message(); ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="<?php echo htmlspecialchars($book['title']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" id="author" class="form-control" 
                           value="<?php echo htmlspecialchars($book['author']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" name="isbn" id="isbn" class="form-control" 
                           value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" 
                           value="<?php echo htmlspecialchars($book['quantity']); ?>" min="0" required>
                </div>
                
                <div class="mb-3">
                    <label for="published_year" class="form-label">Published Year</label>
                    <input type="number" name="published_year" id="published_year" class="form-control" 
                           value="<?php echo htmlspecialchars($book['published_year']); ?>" 
                           min="1900" max="<?php echo date('Y'); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Book</button>
                <a href="view_books.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html> 