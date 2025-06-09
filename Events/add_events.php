<?php include 'config.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evName = $_POST['evName'];
    $evDate = $_POST['evDate'];
    $ovVenue = $_POST['ovVenue'];
    $evRFee = $_POST['evRFee'];

    $stmt = $conn->prepare("INSERT INTO Events (evName, evDate, ovVenue, evRFee) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $evName, $evDate, $ovVenue, $evRFee);
    
    if ($stmt->execute()) {
        header("Location: view_events.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Add Event</h1>
    <form method="POST">
        <input type="text" name="evName" class="form-control mb-2" placeholder="Event Name" required>
        <input type="date" name="evDate" class="form-control mb-2" required>
        <input type="text" name="ovVenue" class="form-control mb-2" placeholder="Venue" required>
        <input type="number" step="0.01" name="evRFee" class="form-control mb-2" placeholder="Fee" required>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
    <a href="view_events.php" class="mt-3 d-block">View Events</a>
</body>
</html>