<?php include 'config.php'; ?>

<?php
if (!isset($_GET['id'])) {
    header("Location: view_events.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Events WHERE evCode = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    header("Location: view_events.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['evCode'];
    $evName = $_POST['evName'];
    $evDate = $_POST['evDate'];
    $ovVenue = $_POST['ovVenue'];
    $evRFee = $_POST['evRFee'];

    $stmt = $conn->prepare("UPDATE Events SET evName=?, evDate=?, ovVenue=?, evRFee=? WHERE evCode=?");
    $stmt->bind_param("sssdi", $evName, $evDate, $ovVenue, $evRFee, $id);
    
    if ($stmt->execute()) {
        header("Location: view_events.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Update Event</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="evCode" value="<?php echo htmlspecialchars($event['evCode']); ?>">
        <input type="text" name="evName" class="form-control mb-2" value="<?php echo htmlspecialchars($event['evName']); ?>" required>
        <input type="date" name="evDate" class="form-control mb-2" value="<?php echo htmlspecialchars($event['evDate']); ?>" required>
        <input type="text" name="ovVenue" class="form-control mb-2" value="<?php echo htmlspecialchars($event['ovVenue']); ?>" required>
        <input type="number" step="0.01" name="evRFee" class="form-control mb-2" value="<?php echo htmlspecialchars($event['evRFee']); ?>" required>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_events.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>