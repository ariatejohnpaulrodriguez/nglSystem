<?php
include '../../../includes/conn.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set("log_errors", 1);
ini_set("error_log", "../../../php-error.log");

function getStatusID($conn, $statusName)
{
    $stmt = $conn->prepare("SELECT status_id FROM statuses WHERE status_name = ?");
    if ($stmt === false) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $statusName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        throw new Exception("Status name '$statusName' not found.");
    }

    $stmt->bind_result($statusID);
    $stmt->fetch();
    $stmt->close();

    return $statusID;
}

function updateStockLevel($conn, $productID, $quantity)
{
    $stmtCheck = $conn->prepare("SELECT stock_id, current_quantity FROM stocks WHERE product_id = ?");
    if ($stmtCheck === false) {
        throw new Exception("Error preparing check statement: " . $conn->error);
    }

    $stmtCheck->bind_param("i", $productID);
    $stmtCheck->execute();
    $inventoryResult = $stmtCheck->get_result();

    if ($inventoryResult->num_rows > 0) {
        $inventoryRow = $inventoryResult->fetch_assoc();
        $newQuantity = $inventoryRow['current_quantity'] + $quantity;

        $stmtUpdate = $conn->prepare("UPDATE stocks SET current_quantity = ? WHERE product_id = ?");
        if ($stmtUpdate === false) {
            throw new Exception("Error preparing update statement: " . $conn->error);
        }

        $stmtUpdate->bind_param("ii", $newQuantity, $productID);
        if (!$stmtUpdate->execute()) {
            throw new Exception("Error executing update statement: " . $stmtUpdate->error);
        }
        $stmtUpdate->close();

    } else {
        $stmtInsert = $conn->prepare("INSERT INTO stocks (product_id, current_quantity) VALUES (?, ?)");
        if ($stmtInsert === false) {
            throw new Exception("Error preparing insert statement: " . $conn->error);
        }

        $stmtInsert->bind_param("ii", $productID, $quantity);
        if (!$stmtInsert->execute()) {
            throw new Exception("Error executing insert statement: " . $stmtInsert->error);
        }
        $stmtInsert->close();
    }

    $stmtCheck->close();
}

$invoiceID = is_numeric($_POST['invoice_id']) ? intval($_POST['invoice_id']) : null;
$action = $_POST['action'] ?? null;

if (!is_numeric($invoiceID) || !in_array($action, ['Approve', 'Reject', 'Pending', 'Cancelled'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received.']);
    exit;
}

$statusName = ($action == 'Approve') ? 'Approved' :
    (($action == 'Reject') ? 'Rejected' :
        (($action == 'Pending') ? 'Pending' : 'Cancelled'));

// Initialize $statusID
$statusID = null;

try {
    mysqli_autocommit($conn, FALSE);

    // Get Status ID from database
    try {
        $statusID = getStatusID($conn, $statusName);
    } catch (Exception $e) {
        throw new Exception("Error getting status ID: " . $e->getMessage());
    }

    // Update invoice status
    if (isset($statusID)) {
        $stmt = $conn->prepare("UPDATE invoices SET status_id = ? WHERE invoice_id = ?");
        if ($stmt === false) {
            throw new Exception("Error preparing invoice update: " . $conn->error);
        }
        $stmt->bind_param("ii", $statusID, $invoiceID);
        if (!$stmt->execute()) {
            throw new Exception("Error executing invoice update: " . $stmt->error);
        }
        $stmt->close();
    }

    if ($statusName === 'Approved') {
        $query = "SELECT product_id, quantity FROM invoice_products WHERE invoice_id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("Error preparing product select: " . $conn->error);
        }
        $stmt->bind_param("i", $invoiceID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            throw new Exception("No products found for invoice ID $invoiceID.");
        }

        while ($row = $result->fetch_assoc()) {
            try {
                updateStockLevel($conn, $row['product_id'], $row['quantity']);
            } catch (Exception $e) {
                throw new Exception("Error updating stock level: " . $e->getMessage());
            }
        }
        $stmt->close();
    }

    mysqli_commit($conn);

    echo json_encode(['status' => 'success', 'message' => "Invoice updated to $statusName successfully."]);

} catch (Exception $e) {
    mysqli_rollback($conn);

    error_log("update-invoice-status.php - Error: " . $e->getMessage() . "\nData: " . json_encode($_POST));

    echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please check the logs.']);

} finally {
    mysqli_autocommit($conn, TRUE);
    mysqli_close($conn);
}
?>