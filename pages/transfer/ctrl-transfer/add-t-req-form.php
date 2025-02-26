<?php
include '../../../includes/conn.php';

// Capture POST data
$postData = $_POST;
$products = $postData['products']; // Array of product details
$postingDate = $postData['posting_date'];
$deliveryDate = $postData['delivery_date'];
$fromCompanyID = $postData['from_company_id'];
$toCompanyID = $postData['to_company_id'];
$poNumber = $postData['po_number'];
$referencePo = $postData['reference_po'];
$drNumber = $postData['dr_number'];
$statusID = $postData['status_id'];

// Add error handling
try {
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

    // Step 6: Insert transfer record into transfers table
    $queryTransfer = "INSERT INTO transfers (from_company_id, to_company_id, po_id, reference_po_id, dr_id, posting_date, delivery_date, status_id) 
    VALUES ('$fromCompanyID', '$toCompanyID', '$poID', '$referenceID', '$drID', '$postingDateID', '$deliveryDateID', '$statusID')";
    if (!mysqli_query($conn, $queryTransfer)) {
        throw new Exception("Error inserting Transfer: " . mysqli_error($conn));
    }
    $transferID = mysqli_insert_id($conn); // Get transfer ID

    // Step 7: Loop through the products and insert them into the transfer_products table
    foreach ($products as $product) {
        $productID = $product['product_id'];
        $quantity = $product['quantity'];
        $brand = $product['brand'];
        $code = $product['code'];
        $description = $product['description'];

        // Insert product details into transfer_products table
        $queryProduct = "INSERT INTO transfer_products (transfer_id, product_id, quantity, brand, code, description) 
                         VALUES ('$transferID', '$productID', '$quantity', '$brand', '$code', '$description')";
        if (!mysqli_query($conn, $queryProduct)) {
            throw new Exception("Error inserting product: " . mysqli_error($conn));
        }
    }

    // Return success response
    echo json_encode(['status' => 'success', 'message' => 'Transfer and products saved successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

mysqli_close($conn);
?>