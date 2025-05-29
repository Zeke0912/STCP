<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die('Sale ID not provided');
}

$sale_id = $_GET['id'];

// Get sale information
$sale_query = "SELECT s.*, 
               CONCAT(c.first_name, ' ', c.last_name) as customer_name,
               c.email as customer_email,
               c.phone as customer_phone
               FROM Sales s
               JOIN Customers c ON s.customer_id = c.customer_id
               WHERE s.sale_id = ?";

$stmt = $conn->prepare($sale_query);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();

if (!$sale) {
    die('Sale not found');
}

// Get sale items
$items_query = "SELECT si.*, p.product_name, p.description
                FROM Sale_Items si
                JOIN Products p ON si.product_id = p.product_id
                WHERE si.sale_id = ?";

$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>

<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4>Customer Information</h4>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($sale['customer_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($sale['customer_email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($sale['customer_phone']); ?></p>
        </div>
        <div class="col-md-6">
            <h4>Sale Information</h4>
            <p><strong>Sale ID:</strong> <?php echo $sale['sale_id']; ?></p>
            <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($sale['sale_date'])); ?></p>
            <p><strong>Payment Method:</strong> <?php echo $sale['payment_method']; ?></p>
        </div>
    </div>

    <h4>Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo format_currency($item['unit_price']); ?></td>
                    <td><?php echo format_currency($item['subtotal']); ?></td>
                </tr>
            <?php endwhile; ?>
            <tr class="table-active">
                <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                <td><strong><?php echo format_currency($sale['total_amount']); ?></strong></td>
            </tr>
        </tbody>
    </table>
</div> 