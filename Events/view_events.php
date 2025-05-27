<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Events List</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM Events");
            if (!$result) {
                echo "<tr><td colspan='6' class='text-center'>Error: " . $conn->error . "</td></tr>";
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['evCode']}</td>
                            <td>{$row['evName']}</td>
                            <td>{$row['evDate']}</td>
                            <td>{$row['ovVenue']}</td>
                            <td>\${$row['evRFee']}</td>
                            <td>
                                <a href='update_event.php?id={$row['evCode']}' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete_event.php?id={$row['evCode']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                            </td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <a href="add_events.php" class="btn btn-success">Add New Event</a>
</body>
</html>