<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Registrations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Registration Statistics</h1>
    
    <?php
    // Total Registrations
    $result = $conn->query("SELECT COUNT(regCode) AS total FROM Registration");
    if (!$result) {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    } else {
        $totalReg = $result->fetch_assoc()['total'];
    }

    // Total Fees Paid
    $result = $conn->query("SELECT SUM(reqFPaid) AS total FROM Registration");
    if (!$result) {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    } else {
        $totalFees = $result->fetch_assoc()['total'];
    }

    // Total Discounts
    $result = $conn->query("
        SELECT SUM(e.evRFee - r.reqFPaid) AS discounts 
        FROM Registration r
        JOIN Participants p ON r.partID = p.partID 
        JOIN Events e ON p.evCode = e.evCode
    ");
    if (!$result) {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    } else {
        $totalDiscounts = $result->fetch_assoc()['discounts'];
    }
    ?>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Registrations</h5>
                    <p class="card-text h2"><?php echo number_format($totalReg ?? 0); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Fees Paid</h5>
                    <p class="card-text h2">$<?php echo number_format($totalFees ?? 0, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Discounts</h5>
                    <p class="card-text h2">$<?php echo number_format($totalDiscounts ?? 0, 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <a href="view_events.php" class="btn btn-primary mt-4">Back to Events</a>
</body>
</html>