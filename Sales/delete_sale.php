<?php
include 'config.php';

if (!isset($_GET['id'])) {
    $_SESSION['message'] = "Sale ID not provided";
    $_SESSION['message_type'] = "danger";
    header("Location: view_sales.php");
    exit();
}

$sale_id = $_GET['id'];

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Get sale items to restore product quantities
    $items_query = "SELECT product_id, quantity FROM Sale_Items WHERE sale_id = ?";
    $stmt = $conn->prepare($items_query);
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();
    $items_result = $stmt->get_result();

    // Restore product quantities
    while ($item = mysqli_fetch_assoc($items_result)) {
        $update_query = "UPDATE Products 
                        SET stock_quantity = stock_quantity + ? 
                        WHERE product_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $update_stmt->execute();
    }

    // Delete sale items
    $delete_items = "DELETE FROM Sale_Items WHERE sale_id = ?";
    $stmt = $conn->prepare($delete_items);
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();

    // Delete sale
    $delete_sale = "DELETE FROM Sales WHERE sale_id = ?";
    $stmt = $conn->prepare($delete_sale);
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();

    mysqli_commit($conn);
    $_SESSION['message'] = "Sale deleted successfully";
    $_SESSION['message_type'] = "success";

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['message'] = "Error deleting sale: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header("Location: view_sales.php");
exit(); 