<?php
include 'config.php';

if (!isset($_GET['id'])) {
    $_SESSION['message'] = "Sale ID not provided";
    $_SESSION['message_type'] = "danger";
    header("Location: view_sales.php");
    exit();
}

$sale_id = $_GET['id'];

// Get all products
$products_query = "SELECT * FROM Products";
$products_result = mysqli_query($conn, $products_query);

// Get all customers
$customers_query = "SELECT * FROM Customers";
$customers_result = mysqli_query($conn, $customers_query);

// Get sale information
$sale_query = "SELECT * FROM Sales WHERE sale_id = ?";
$stmt = $conn->prepare($sale_query);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();

if (!$sale) {
    $_SESSION['message'] = "Sale not found";
    $_SESSION['message_type'] = "danger";
    header("Location: view_sales.php");
    exit();
}

// Get sale items
$items_query = "SELECT si.*, p.product_name, p.stock_quantity 
                FROM Sale_Items si
                JOIN Products p ON si.product_id = p.product_id
                WHERE si.sale_id = ?";
$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$sale_items = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $sale_date = $_POST['sale_date'];
    $payment_method = $_POST['payment_method'];
    $products = $_POST['products'];
    $quantities = $_POST['quantities'];
    $original_quantities = $_POST['original_quantities'];
    $original_products = $_POST['original_products'];
    
    mysqli_begin_transaction($conn);
    
    try {
        // Update sale information
        $update_sale = "UPDATE Sales SET customer_id = ?, sale_date = ?, payment_method = ? WHERE sale_id = ?";
        $stmt = $conn->prepare($update_sale);
        $stmt->bind_param("issi", $customer_id, $sale_date, $payment_method, $sale_id);
        $stmt->execute();

        // Restore original quantities
        for ($i = 0; $i < count($original_products); $i++) {
            $restore_query = "UPDATE Products 
                            SET stock_quantity = stock_quantity + ? 
                            WHERE product_id = ?";
            $restore_stmt = $conn->prepare($restore_query);
            $restore_stmt->bind_param("ii", $original_quantities[$i], $original_products[$i]);
            $restore_stmt->execute();
        }

        // Delete old sale items
        $delete_items = "DELETE FROM Sale_Items WHERE sale_id = ?";
        $stmt = $conn->prepare($delete_items);
        $stmt->bind_param("i", $sale_id);
        $stmt->execute();

        // Insert new sale items and update stock
        $total_amount = 0;
        for ($i = 0; $i < count($products); $i++) {
            if ($quantities[$i] > 0) {
                // Get product details
                $prod_stmt = $conn->prepare("SELECT unit_price, stock_quantity FROM Products WHERE product_id = ?");
                $prod_stmt->bind_param("i", $products[$i]);
                $prod_stmt->execute();
                $product = $prod_stmt->get_result()->fetch_assoc();
                
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
        $_SESSION['message'] = "Sale updated successfully";
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
    <title>Update Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">
    <h1>Update Sale</h1>
    <?php show_message(); ?>
    
    <form method="POST" id="saleForm">
        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                <?php while($customer = mysqli_fetch_assoc($customers_result)): ?>
                    <option value="<?php echo $customer['customer_id']; ?>"
                            <?php echo ($customer['customer_id'] == $sale['customer_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Sale Date</label>
            <input type="date" name="sale_date" class="form-control" required 
                   value="<?php echo $sale['sale_date']; ?>">
        </div>
        
        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <?php
                $methods = ['Cash', 'Credit Card', 'Debit Card', 'Online'];
                foreach ($methods as $method):
                ?>
                    <option value="<?php echo $method; ?>"
                            <?php echo ($method == $sale['payment_method']) ? 'selected' : ''; ?>>
                        <?php echo $method; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div id="products-container">
            <h3>Products</h3>
            <?php while ($item = mysqli_fetch_assoc($sale_items)): ?>
                <div class="product-entry mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="products[]" class="form-control product-select" required>
                                <?php 
                                mysqli_data_seek($products_result, 0);
                                while($product = mysqli_fetch_assoc($products_result)): 
                                ?>
                                    <option value="<?php echo $product['product_id']; ?>"
                                            data-price="<?php echo $product['unit_price']; ?>"
                                            data-stock="<?php echo $product['stock_quantity']; ?>"
                                            <?php echo ($product['product_id'] == $item['product_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($product['product_name']); ?> 
                                        (<?php echo format_currency($product['unit_price']); ?>) - 
                                        Stock: <?php echo $product['stock_quantity']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="quantities[]" class="form-control quantity-input" 
                                   min="1" value="<?php echo $item['quantity']; ?>" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-product">Remove</button>
                        </div>
                    </div>
                    <input type="hidden" name="original_products[]" value="<?php echo $item['product_id']; ?>">
                    <input type="hidden" name="original_quantities[]" value="<?php echo $item['quantity']; ?>">
                </div>
            <?php endwhile; ?>
        </div>
        
        <button type="button" class="btn btn-secondary mb-3" id="add-product">Add Another Product</button>
        <button type="submit" class="btn btn-primary">Update Sale</button>
        <a href="view_sales.php" class="btn btn-link">Back to Sales List</a>
    </form>

    <script>
    $(document).ready(function() {
        // Add new product entry
        $('#add-product').click(function() {
            var productEntry = $('.product-entry:first').clone();
            productEntry.find('input[name="quantities[]"]').val('1');
            productEntry.find('select').val('');
            productEntry.find('input[type="hidden"]').remove();
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