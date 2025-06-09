<?php 
include 'config.php';

// Get all products
$products_query = "SELECT * FROM Products WHERE stock_quantity > 0";
$products_result = mysqli_query($conn, $products_query);

// Get all customers
$customers_query = "SELECT * FROM Customers";
$customers_result = mysqli_query($conn, $customers_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $sale_date = $_POST['sale_date'];
    $payment_method = $_POST['payment_method'];
    $products = $_POST['products'];
    $quantities = $_POST['quantities'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert sale record
        $total_amount = 0;
        $stmt = $conn->prepare("INSERT INTO Sales (customer_id, sale_date, total_amount, payment_method) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isds", $customer_id, $sale_date, $total_amount, $payment_method);
        $stmt->execute();
        $sale_id = $stmt->insert_id;
        
        // Insert sale items and calculate total
        for ($i = 0; $i < count($products); $i++) {
            if ($quantities[$i] > 0) {
                // Get product details
                $prod_stmt = $conn->prepare("SELECT unit_price, stock_quantity FROM Products WHERE product_id = ?");
                $prod_stmt->bind_param("i", $products[$i]);
                $prod_stmt->execute();
                $prod_result = $prod_stmt->get_result();
                $product = $prod_result->fetch_assoc();
                
                // Calculate subtotal
                $subtotal = $product['unit_price'] * $quantities[$i];
                $total_amount += $subtotal;
                
                // Insert sale item
                $item_stmt = $conn->prepare("INSERT INTO Sale_Items (sale_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
                $item_stmt->bind_param("iiids", $sale_id, $products[$i], $quantities[$i], $product['unit_price'], $subtotal);
                $item_stmt->execute();
                
                // Update product stock
                $new_quantity = $product['stock_quantity'] - $quantities[$i];
                $update_stmt = $conn->prepare("UPDATE Products SET stock_quantity = ? WHERE product_id = ?");
                $update_stmt->bind_param("ii", $new_quantity, $products[$i]);
                $update_stmt->execute();
            }
        }
        
        // Update total amount
        $update_total = $conn->prepare("UPDATE Sales SET total_amount = ? WHERE sale_id = ?");
        $update_total->bind_param("di", $total_amount, $sale_id);
        $update_total->execute();
        
        mysqli_commit($conn);
        $_SESSION['message'] = "Sale added successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: view_sales.php");
        exit();
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">
    <h1>Add New Sale</h1>
    <?php show_message(); ?>
    
    <form method="POST" id="saleForm">
        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                <?php while($customer = mysqli_fetch_assoc($customers_result)): ?>
                    <option value="<?php echo $customer['customer_id']; ?>">
                        <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Sale Date</label>
            <input type="date" name="sale_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <option value="Cash">Cash</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="Online">Online</option>
            </select>
        </div>
        
        <div id="products-container">
            <h3>Products</h3>
            <div class="product-entry mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <select name="products[]" class="form-control product-select" required>
                            <option value="">Select Product</option>
                            <?php 
                            mysqli_data_seek($products_result, 0);
                            while($product = mysqli_fetch_assoc($products_result)): 
                            ?>
                                <option value="<?php echo $product['product_id']; ?>" 
                                        data-price="<?php echo $product['unit_price']; ?>"
                                        data-stock="<?php echo $product['stock_quantity']; ?>">
                                    <?php echo htmlspecialchars($product['product_name']); ?> 
                                    (<?php echo format_currency($product['unit_price']); ?>) - 
                                    Stock: <?php echo $product['stock_quantity']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="quantities[]" class="form-control quantity-input" 
                               min="1" value="1" required placeholder="Quantity">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-product">Remove</button>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-secondary mb-3" id="add-product">Add Another Product</button>
        <button type="submit" class="btn btn-primary">Save Sale</button>
        <a href="view_sales.php" class="btn btn-link">Back to Sales List</a>
    </form>

    <script>
    $(document).ready(function() {
        // Add new product entry
        $('#add-product').click(function() {
            var productEntry = $('.product-entry:first').clone();
            productEntry.find('input').val('1');
            productEntry.find('select').val('');
            $('#products-container').append(productEntry);
        });
        
        // Remove product entry
        $(document).on('click', '.remove-product', function() {
            if ($('.product-entry').length > 1) {
                $(this).closest('.product-entry').remove();
            }
        });
        
        // Validate quantity against stock
        $(document).on('change', '.quantity-input, .product-select', function() {
            var row = $(this).closest('.product-entry');
            var select = row.find('.product-select');
            var quantity = row.find('.quantity-input');
            var option = select.find('option:selected');
            
            if (option.length && option.data('stock')) {
                quantity.attr('max', option.data('stock'));
                if (parseInt(quantity.val()) > option.data('stock')) {
                    quantity.val(option.data('stock'));
                }
            }
        });
    });
    </script>
</body>
</html> 