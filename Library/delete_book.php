<?php include 'config.php'; ?>

<?php
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "No book specified";
    $_SESSION['message_type'] = "danger";
    header("Location: view_books.php");
    exit();
}

$id = $_GET['id'];

// Check if book exists
$check = $conn->prepare("SELECT book_id FROM Books WHERE book_id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = "Book not found";
    $_SESSION['message_type'] = "danger";
    header("Location: view_books.php");
    exit();
}

// Check if book is currently borrowed
$check = $conn->prepare("SELECT COUNT(*) as count FROM Loans WHERE book_id = ? AND return_date IS NULL");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();
$count = $result->fetch_assoc()['count'];

if ($count > 0) {
    $_SESSION['message'] = "Cannot delete: Book is currently borrowed";
    $_SESSION['message_type'] = "danger";
    header("Location: view_books.php");
    exit();
}

// Delete the book
$stmt = $conn->prepare("DELETE FROM Books WHERE book_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Book deleted successfully";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error deleting book: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}

header("Location: view_books.php");
exit();
?> 