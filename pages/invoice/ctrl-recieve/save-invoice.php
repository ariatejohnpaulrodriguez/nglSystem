<?php
// Include database connection
include '../../../includes/conn.php';

// Set response header to JSON
header('Content-Type: application/json');

// Log errors to a file
ini_set("log_errors", 1);
ini_set("error_log", "../../php-error.log");

// Get JSON data from the request
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

// Check if JSON decoding failed
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    error_log("Error decoding JSON: " . json_last_error_msg());
    echo json_encode(array("status" => "error", "message" => "Invalid JSON data."));
    exit;
}

// Function to sanitize data
function sanitize($conn, $data)
{
    return mysqli_real_escape_string($conn, trim($data));
}

try {
    // Extract data from the JSON data
    $products = $data['products'];
    $postingDate = sanitize($conn, $data['posting_date']);
    $deliveryDate = sanitize($conn, $data['delivery_date']);
    $fromCompanyID = intval($data['from_company_id']); // Convert to integer
    $toCompanyID = intval($data['to_company_id']); // Convert to integer
    $poNumber = sanitize($conn, $data['po_number']);
    $referencePo = sanitize($conn, $data['reference_po']);
    $drNumber = sanitize($conn, $data['dr_number']);

    // --------------------------------------------------------------------
    //  DATABASE INSERTION SECTION
    // --------------------------------------------------------------------

    // Disable autocommit to start a transaction
    mysqli_autocommit($conn, FALSE);

    // Step 1: Insert posting date into the dates table
    $postingDateQuery = "INSERT INTO dates (date_value) VALUES ('$postingDate')";
    if (!mysqli_query($conn, $postingDateQuery)) {
        throw new Exception("Error inserting posting date: " . mysqli_error($conn));
    }
    $postingDateID = mysqli_insert_id($conn);  // Get posting date ID

    // Step 2: Insert delivery date into the dates table
    $deliveryDateQuery = "INSERT INTO dates (date_value) VALUES ('$deliveryDate')";
    if (!mysqli_query($conn, $deliveryDateQuery)) {
        throw new Exception("Error inserting delivery date: " . mysqli_error($conn));
    }
    $deliveryDateID = mysqli_insert_id($conn);  // Get delivery date ID

    // Step 3: Insert into purchase_orders table
    $poQuery = "INSERT INTO purchase_orders (po_number) VALUES ('$poNumber')";
    if (!mysqli_query($conn, $poQuery)) {
        throw new Exception("Error inserting PO: " . mysqli_error($conn));
    }
    $poID = mysqli_insert_id($conn);  // Get PO ID

    // Step 4: Insert into reference_pos table
    $referenceQuery = "INSERT INTO reference_pos (reference_po) VALUES ('$referencePo')";
    if (!mysqli_query($conn, $referenceQuery)) {
        throw new Exception("Error inserting reference PO: " . mysqli_error($conn));
    }
    $referenceID = mysqli_insert_id($conn);  // Get reference ID

    // Step 5: Insert into delivery_receipts table
    $drQuery = "INSERT INTO delivery_receipts (dr_number) VALUES ('$drNumber')";
    if (!mysqli_query($conn, $drQuery)) {
        throw new Exception("Error inserting DR: " . mysqli_error($conn));
    }
    $drID = mysqli_insert_id($conn);  // Get DR ID

    // Step 6: Insert invoice record into invoices table
    $queryInvoice = "INSERT INTO invoices (from_company_id, to_company_id, po_id, reference_po_id, dr_id, posting_date, delivery_date)
    VALUES ('$fromCompanyID', '$toCompanyID', '$poID', '$referenceID', '$drID', '$postingDateID', '$deliveryDateID')";
    if (!mysqli_query($conn, $queryInvoice)) {
        throw new Exception("Error inserting invoice: " . mysqli_error($conn));
    }
    $invoiceID = mysqli_insert_id($conn); // Get invoice ID

    // Step 7: Loop through the products and insert them into the invoice_products table
    foreach ($products as $product) {
        $productID = intval($product['product_id']); // Convert to integer
        $quantity = intval($product['quantity']); // Convert to integer
        $brand = sanitize($conn, $product['brand']);
        $code = sanitize($conn, $product['code']);
        $description = sanitize($conn, $product['description']);

        // Insert product details into invoice_products table
        $queryProduct = "INSERT INTO invoice_products (invoice_id, product_id, quantity, brand, code, description)
                         VALUES ('$invoiceID', '$productID', '$quantity', '$brand', '$code', '$description')";
        if (!mysqli_query($conn, $queryProduct)) {
            throw new Exception("Error inserting product: " . mysqli_error($conn));
        }

        // Check if the product exists in the stocks table
        $checkStockQuery = "SELECT * FROM stocks WHERE product_id = '$productID'";
        $checkStockResult = mysqli_query($conn, $checkStockQuery);

        if (mysqli_num_rows($checkStockResult) > 0) {
            // If it does, update the current_quantity
            $updateStockQuery = "UPDATE stocks SET current_quantity = current_quantity + $quantity WHERE product_id = $productID";
            if (!mysqli_query($conn, $updateStockQuery)) {
                throw new Exception("Error updating stock: " . mysqli_error($conn));
            }
        } else {
            // If it doesn't, insert a new row
            $insertStockQuery = "INSERT INTO stocks (product_id, current_quantity) VALUES ('$productID', '$quantity')";
            if (!mysqli_query($conn, $insertStockQuery)) {
                throw new Exception("Error inserting stock: " . mysqli_error($conn));
            }
        }
    }

    // Commit the transaction
    mysqli_commit($conn);

    // Return success response
    echo json_encode(array("status" => "success", "message" => "Invoice and products saved successfully."));

} catch (Exception $e) {
    // Rollback the transaction
    mysqli_rollback($conn);

    // Log the error
    error_log("Error in save_invoice.php: " . $e->getMessage() . "\nData: " . json_encode($data));

    // Send an error response
    echo json_encode(array("status" => "error", "message" => "Error saving invoice. Please check the logs."));

} finally {
    // Reset autocommit and close the connection
    mysqli_autocommit($conn, TRUE);
    if ($conn) {
        $conn->close();
    }
}
?>