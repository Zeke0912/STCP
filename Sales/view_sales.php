<?php
include 'config.php';

// Get all sales with customer information
$query = "SELECT s.*, 
          CONCAT(c.first_name, ' ', c.last_name) as customer_name,
          COUNT(si.sale_item_id) as items_count
          FROM Sales s
          JOIN Customers c ON s.customer_id = c.customer_id
          LEFT JOIN Sale_Items si ON s.sale_id = si.sale_id
          GROUP BY s.sale_id
          ORDER BY s.sale_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sales List</h1>
        <a href="add_sale.php" class="btn btn-primary">Add New Sale</a>
    </div>

    <?php show_message(); ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sale = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $sale['sale_id']; ?></td>
                    <td><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($sale['sale_date'])); ?></td>
                    <td><?php echo $sale['items_count']; ?> items</td>
                    <td><?php echo format_currency($sale['total_amount']); ?></td>
                    <td><?php echo $sale['payment_method']; ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info view-details" 
                                    data-sale-id="<?php echo $sale['sale_id']; ?>">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <a href="update_sale.php?id=<?php echo $sale['sale_id']; ?>" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-sale" 
                                    data-sale-id="<?php echo $sale['sale_id']; ?>">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Sale Details Modal -->
    <div class="modal fade" id="saleDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sale Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="saleDetails"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // View sale details
        $('.view-details').click(function() {
            var saleId = $(this).data('sale-id');
            $.get('get_sale_details.php', {id: saleId}, function(data) {
                $('#saleDetails').html(data);
                $('#saleDetailsModal').modal('show');
            });
        });

        // Delete sale confirmation
        $('.delete-sale').click(function() {
            var saleId = $(this).data('sale-id');
            if (confirm('Are you sure you want to delete this sale?')) {
                window.location.href = 'delete_sale.php?id=' + saleId;
            }
        });
    });
    </script>
</body>
</html> 