<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

// Set response header to JSON
header('Content-Type: application/json');

// Log errors to a file
ini_set("log_errors", 1);
ini_set("error_log", "../../php-error.log");

// Get JSON data from the request
$json_data = file_get_contents("php://input");

// Log raw JSON data received
error_log("JSON data received: " . $json_data);

$data = json_decode($json_data, true);

// Check if JSON decoding failed
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    error_log("Error decoding JSON: " . json_last_error_msg());
    $_SESSION['error'] = "Invalid JSON data.";
    echo json_encode(array("status" => "error", "message" => $_SESSION['error']));
    exit;
}

// Log decoded data
error_log("Decoded data: " . print_r($data, true));

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
    $fromCompanyID = intval($data['from_company_id']);
    $toCompanyID = intval($data['to_company_id']);
    $poNumber = sanitize($conn, $data['po_number']);
    $referencePo = sanitize($conn, $data['reference_po']);
    $drNumber = sanitize($conn, $data['dr_number']);

    // Log product count
    error_log("Total Products Received: " . count($products));

    // Start a transaction
    mysqli_autocommit($conn, FALSE);

    // Insert posting date
    $postingDateQuery = "INSERT INTO dates (date_value) VALUES ('$postingDate')";
    if (!mysqli_query($conn, $postingDateQuery)) {
        throw new Exception("Error inserting posting date: " . mysqli_error($conn));
    }
    $postingDateID = mysqli_insert_id($conn);

    // Insert delivery date
    $deliveryDateQuery = "INSERT INTO dates (date_value) VALUES ('$deliveryDate')";
    if (!mysqli_query($conn, $deliveryDateQuery)) {
        throw new Exception("Error inserting delivery date: " . mysqli_error($conn));
    }
    $deliveryDateID = mysqli_insert_id($conn);

    // Insert purchase order
    $poQuery = "INSERT INTO purchase_orders (po_number) VALUES ('$poNumber')";
    if (!mysqli_query($conn, $poQuery)) {
        throw new Exception("Error inserting PO: " . mysqli_error($conn));
    }
    $poID = mysqli_insert_id($conn);

    // Insert reference PO
    $referenceQuery = "INSERT INTO reference_pos (reference_po) VALUES ('$referencePo')";
    if (!mysqli_query($conn, $referenceQuery)) {
        throw new Exception("Error inserting reference PO: " . mysqli_error($conn));
    }
    $referenceID = mysqli_insert_id($conn);

    // Insert delivery receipt
    $drQuery = "INSERT INTO delivery_receipts (dr_number) VALUES ('$drNumber')";
    if (!mysqli_query($conn, $drQuery)) {
        throw new Exception("Error inserting DR: " . mysqli_error($conn));
    }
    $drID = mysqli_insert_id($conn);

    // Insert invoice
    $queryInvoice = "INSERT INTO invoices (from_company_id, to_company_id, po_id, reference_po_id, dr_id, posting_date, delivery_date)
    VALUES ('$fromCompanyID', '$toCompanyID', '$poID', '$referenceID', '$drID', '$postingDateID', '$deliveryDateID')";
    if (!mysqli_query($conn, $queryInvoice)) {
        throw new Exception("Error inserting invoice: " . mysqli_error($conn));
    }
    $invoiceID = mysqli_insert_id($conn);

    // Process products
    foreach ($products as $product) {
        $productID = intval($product['product_id']);
        $quantity = intval($product['quantity']);
        $code = mysqli_real_escape_string($conn, $product['code']);
        $brand = mysqli_real_escape_string($conn, $product['brand']);
        $description = mysqli_real_escape_string($conn, $product['description']);

        error_log("Processing Product - Data: " . print_r($product, true));

        // Fetch product base unit
        $productQuery = "SELECT unit_id FROM products WHERE product_id = '$productID'";
        $productResult = mysqli_query($conn, $productQuery);
        $productRow = mysqli_fetch_assoc($productResult);

        if (!$productRow) {
            error_log("Product ID: $productID - No matching record found in products table!");
            continue;
        }

        $baseUnitID = $productRow['unit_id'];
        error_log("Product ID: $productID - Base Unit ID: " . $baseUnitID);

        if (!isset($product['unit_id'])) {
            error_log("Missing Target Unit ID for Product ID: $productID. Defaulting to Base Unit ID ($baseUnitID).");
            $product['unit_id'] = $baseUnitID; // Default to base unit
        }

        // Skip conversion if units are the same
        if ($baseUnitID == $product['unit_id']) {
            error_log("Skipping conversion for Product ID: $productID | Base Unit and Target Unit are the same.");
            $conversionFactor = 1; // No conversion needed
            $targetUnitID = $baseUnitID;
        } else {
            // Fetch conversion details
            $conversionQuery = "SELECT target_unit_id, conversion_factor 
                        FROM unit_conversions 
                        WHERE base_unit_id = ? 
                        AND target_unit_id = ?";
            $stmt = $conn->prepare($conversionQuery);
            $stmt->bind_param("ii", $baseUnitID, $product['unit_id']);
            $stmt->execute();
            $conversionResult = $stmt->get_result();

            if ($conversionRow = mysqli_fetch_assoc($conversionResult)) {
                $targetUnitID = $conversionRow['target_unit_id'];
                $conversionFactor = floatval($conversionRow['conversion_factor']);
                error_log("Conversion Found - Product ID: $productID | Base Unit: $baseUnitID → Target Unit: $targetUnitID | Factor: $conversionFactor");
            } else {
                error_log("No Conversion Found for Product ID: $productID - Using Default Target Unit.");
                $targetUnitID = $baseUnitID;  // Default to base unit
                $conversionFactor = 1;       // Default to no conversion
            }
        }

        // Fetch computation rule
        $computationQuery = "SELECT operation FROM unit_computations 
                             WHERE base_unit_id = '$baseUnitID' 
                             AND target_unit_id = '$targetUnitID'
                             AND transaction_type = 'incoming'";
        $computationResult = mysqli_query($conn, $computationQuery);
        $hasComputation = mysqli_num_rows($computationResult) > 0;

        if ($hasComputation) {
            $computationRow = mysqli_fetch_assoc($computationResult);
            $operation = $computationRow['operation'];
            error_log("Computation Rule Found - Product ID: $productID | Operation: $operation");
        } else {
            error_log("No Computation Rule Found for Product ID: $productID");
        }

        // Convert quantity
        $convertedQuantity = $quantity * $conversionFactor;

        // Default final quantity
        $finalQuantity = $convertedQuantity;

        // Apply computation rule
        if ($hasComputation) {
            switch ($operation) {
                case '+':
                    $finalQuantity = $convertedQuantity; // No addition necessary
                    break;
                case '-':
                    $finalQuantity = $convertedQuantity - $quantity;
                    break;
                case '*':
                    $finalQuantity = $convertedQuantity * $quantity;
                    break;
                case '/':
                    $finalQuantity = ($quantity != 0) ? $convertedQuantity / $quantity : 0;
                    break;
            }
            error_log("Computation Applied - Product ID: $productID | Final Quantity: $finalQuantity");
        }

        error_log("Stock Processing - Product ID: $productID | Final Quantity to Update: $finalQuantity");

        // Insert into invoice_products
        $insertProductQuery = "INSERT INTO invoice_products (invoice_id, product_id, quantity, code, brand, description)
                               VALUES ('$invoiceID', '$productID', '$convertedQuantity', '$code', '$brand', '$description')";
        if (!mysqli_query($conn, $insertProductQuery)) {
            throw new Exception("Error inserting product into invoice_products: " . mysqli_error($conn));
        }

        // Update or insert into stocks
        $checkStockQuery = "SELECT * FROM stocks WHERE product_id = '$productID'";
        $checkStockResult = mysqli_query($conn, $checkStockQuery);

        if (mysqli_num_rows($checkStockResult) > 0) {
            $updateStockQuery = "UPDATE stocks SET current_quantity = current_quantity + $finalQuantity WHERE product_id = '$productID'";
            if (!mysqli_query($conn, $updateStockQuery)) {
                throw new Exception("Error updating stock: " . mysqli_error($conn));
            }
        } else {
            $insertStockQuery = "INSERT INTO stocks (product_id, current_quantity) VALUES ('$productID', '$finalQuantity')";
            if (!mysqli_query($conn, $insertStockQuery)) {
                throw new Exception("Error inserting stock: " . mysqli_error($conn));
            }
        }
    }

    // Set the transaction type explicitly
    $transactionType = 'invoices'; // Use 'invoices' for incoming transactions

    // === INSERT INTO TRANSACTIONS TABLE ===
    $transactionQuery = "INSERT INTO transactions (transaction_type, from_company_id, to_company_id, posting_date, delivery_date, dr_id, po_id, reference_po_id, product_id, quantity, unit_id, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtTransaction = $conn->prepare($transactionQuery);

    foreach ($products as $product) {
        $currentTimestamp = date("Y-m-d H:i:s"); // Get current timestamp
        $stmtTransaction->bind_param(
            "siiiiiiiiiss",
            $transactionType, // Set as 'invoices'
            $fromCompanyID,
            $toCompanyID,
            $postingDateID,
            $deliveryDateID,
            $drID,
            $poID,
            $referenceID,
            $product['product_id'],
            $product['quantity'],
            $product['unit_id'],
            $currentTimestamp
        );

        if (!$stmtTransaction->execute()) {
            throw new Exception("Error inserting transaction: " . mysqli_error($conn));
        }
    }
    $stmtTransaction->close();
    // === END OF TRANSACTIONS INSERTION ===

    mysqli_commit($conn);
    $_SESSION['success'] = "Invoice and products saved successfully!";
    echo json_encode(array("status" => "success", "message" => $_SESSION['success']));

} catch (Exception $e) {
    mysqli_rollback($conn);
    error_log("Error: " . $e->getMessage());
    echo json_encode(array("status" => "error", "message" => "Error saving invoice. Check logs."));
} finally {
    mysqli_autocommit($conn, TRUE);
    $conn->close();
}
?>