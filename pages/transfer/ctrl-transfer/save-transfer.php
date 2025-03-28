<?php
include '../../../includes/conn.php';
session_start();

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
    $_SESSION['error'] = "Invalid JSON data.";
    echo json_encode(array("status" => "error", "message" => $_SESSION['error']));
    exit;
}

// Function to sanitize data
function sanitize($conn, $data)
{
    return mysqli_real_escape_string($conn, trim($data));
}

function getStatusID($conn, $statusName)
{
    $query = "SELECT status_id FROM statuses WHERE status_name = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        throw new Exception("Error preparing status query: " . $conn->error);
    }
    $stmt->bind_param("s", $statusName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return intval($row['status_id']);
    } else {
        throw new Exception("Status '$statusName' not found.");
    }
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
    $statusID = getStatusID($conn, 'Pending'); // Set initial status as 'Pending'

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

    // Step 6: Insert transfer record into transfers table
    $transferQuery = "INSERT INTO transfers (from_company_id, to_company_id, po_id, reference_po_id, dr_id, posting_date, delivery_date, status_id)
    VALUES ('$fromCompanyID', '$toCompanyID', '$poID', '$referenceID', '$drID', '$postingDateID', '$deliveryDateID', '$statusID')";
    if (!mysqli_query($conn, $transferQuery)) {
        throw new Exception("Error inserting transfer: " . mysqli_error($conn));
    }
    $transferID = mysqli_insert_id($conn); // Get transfer ID

    // **STEP 7: Insert products into transfer_products table**
    foreach ($products as $product) {
        $productID = intval($product['product_id']);  //Sanitize: Ensure it's an integer
        $quantity = intval($product['quantity']);      //Sanitize: Ensure it's an integer
        $code = sanitize($conn, $product['code']);      //Sanitize
        $brand = sanitize($conn, $product['brand']);    //Sanitize
        $description = sanitize($conn, $product['description']); //Sanitize

        $transferProductQuery = "INSERT INTO transfer_products (transfer_id, product_id, quantity, code, brand, description)
                                VALUES ('$transferID', '$productID', '$quantity', '$code', '$brand', '$description')";

        if (!mysqli_query($conn, $transferProductQuery)) {
            throw new Exception("Error inserting transfer product: " . mysqli_error($conn));
        }
    }
    // Commit the transaction
    mysqli_commit($conn);

    // Store success message in session
    $_SESSION['success'] = "Transfer and products saved successfully!";

    // Return success response
    echo json_encode(array("status" => "success", "message" => $_SESSION['success']));

} catch (Exception $e) {
    // Rollback the transaction
    mysqli_rollback($conn);

    // Log the error
    error_log("Error in save_transfer.php: " . $e->getMessage() . "\nData: " . json_encode($data));

    // Store error message in session
    $_SESSION['error'] = "Error saving transfer. Please check the logs.";

    // Send an error response
    echo json_encode(array("status" => "error", "message" => $_SESSION['error']));

} finally {
    // Reset autocommit and close the connection
    mysqli_autocommit($conn, TRUE);
    if ($conn) {
        $conn->close();
    }
}
?>