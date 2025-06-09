<?php include 'config.php'; ?>

<?php
if (!isset($_GET['id'])) {
    header("Location: view_events.php");
    exit();
}

$id = $_GET['id'];

// Check if event has any participants
$check = $conn->prepare("SELECT COUNT(*) as count FROM Participants WHERE evCode = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();
$count = $result->fetch_assoc()['count'];

if ($count > 0) {
    $_SESSION['error'] = "Cannot delete event: There are participants registered for this event.";
    header("Location: view_events.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM Events WHERE evCode = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: view_events.php");
    exit();
} else {
    $_SESSION['error'] = "Error deleting event: " . $stmt->error;
    header("Location: view_events.php");
    exit();
}
?>