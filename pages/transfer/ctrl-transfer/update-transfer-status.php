<?php
include '../../../includes/conn.php';
include '../../../includes/session.php'; // Include session to access $_SESSION variables

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
        $newQuantity = $inventoryRow['current_quantity'] - $quantity; // Subtract for transfers

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
        throw new Exception("Stock does not exists, please populate first");
    }

    $stmtCheck->close();
}

// Process the request
$transferID = is_numeric($_POST['transfer_id']) ? intval($_POST['transfer_id']) : null;
$action = $_POST['action'] ?? null;

if (!is_numeric($transferID) || !in_array($action, ['Approve', 'Reject', 'Pending', 'Cancelled'])) {
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
    $statusID = getStatusID($conn, $statusName);

    // Validate the user's role permissions
    $role_id = $_SESSION['role_id']; // User's role ID from session
    $permissionQuery = "SELECT 1 FROM role_status_permissions WHERE role_id = ? AND status_id = ?";
    $stmt = $conn->prepare($permissionQuery);
    $stmt->bind_param("ii", $role_id, $statusID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'You do not have permission to perform this action.']);
        exit;
    }
    $stmt->close();

    // Update transfer status
    $stmtUpdateStatus = $conn->prepare("UPDATE transfers SET status_id = ? WHERE transfer_id = ?");
    if ($stmtUpdateStatus === false) {
        throw new Exception("Error preparing transfer update statement: " . $conn->error);
    }
    $stmtUpdateStatus->bind_param("ii", $statusID, $transferID);
    if (!$stmtUpdateStatus->execute()) {
        throw new Exception("Error executing transfer update statement: " . $stmtUpdateStatus->error);
    }
    $stmtUpdateStatus->close();

    // Process approval logic
    if ($statusName === 'Approved') {
        // Retrieve transfer details
        $transferDetailsQuery = "SELECT from_company_id, to_company_id, posting_date, delivery_date, dr_id, po_id, reference_po_id FROM transfers WHERE transfer_id = ?";
        $stmtTransferDetails = $conn->prepare($transferDetailsQuery);
        if ($stmtTransferDetails === false) {
            throw new Exception("Error preparing transfer details query: " . $conn->error);
        }
        $stmtTransferDetails->bind_param("i", $transferID);
        $stmtTransferDetails->execute();
        $transferDetailsResult = $stmtTransferDetails->get_result();

        if ($transferDetailsResult->num_rows === 0) {
            throw new Exception("Transfer details not found for transfer ID $transferID.");
        }

        $transferDetails = $transferDetailsResult->fetch_assoc();
        $fromCompanyID = $transferDetails['from_company_id'];
        $toCompanyID = $transferDetails['to_company_id'];
        $postingDate = $transferDetails['posting_date'];
        $deliveryDate = $transferDetails['delivery_date'];
        $drID = $transferDetails['dr_id'];
        $poID = $transferDetails['po_id'];
        $referencePoID = $transferDetails['reference_po_id'];
        $stmtTransferDetails->close();

        // Fetch products for the transfer
        $query = "SELECT product_id, quantity, code, brand, description FROM transfer_products WHERE transfer_id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("Error preparing product select: " . $conn->error);
        }
        $stmt->bind_param("i", $transferID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            throw new Exception("No products found for transfer ID $transferID.");
        }

        // Set the transaction type explicitly
        $transactionType = 'transfers'; // Use 'transfers' for outgoing transactions

        while ($row = $result->fetch_assoc()) {
            $productID = $row['product_id'];
            $quantity = $row['quantity'];

            // Retrieve company from stock
            $stockQuery = "SELECT product_id FROM stocks WHERE product_id = ?";
            $stmtStock = $conn->prepare($stockQuery);
            if ($stmtStock === false) {
                throw new Exception("Error preparing stock check: " . $conn->error);
            }
            $stmtStock->bind_param("i", $productID);
            $stmtStock->execute();
            $stockResult = $stmtStock->get_result();
            $currentCompanyID = $stockResult->fetch_assoc()['product_id'];
            $stmtStock->close();

            // Check if enough stock is available
            $stockQuery = "SELECT current_quantity FROM stocks WHERE product_id = ?";
            $stmtStock = $conn->prepare($stockQuery);
            if ($stmtStock === false) {
                throw new Exception("Error preparing stock check: " . $conn->error);
            }
            $stmtStock->bind_param("i", $productID);
            $stmtStock->execute();
            $stockResult = $stmtStock->get_result();

            if ($stockResult->num_rows === 0) {
                throw new Exception("Stock not found for product ID {$row['product_id']}.");
            } else if ($stockResult->fetch_assoc()['current_quantity'] < $quantity) {
                throw new Exception("Insufficient stock for product ID {$row['product_id']}.");
            }
            $stmtStock->close();

            //Update stock level
            updateStockLevel($conn, $productID, $quantity);

            // Retrieve unit_id
            $unitQuery = "SELECT unit_id FROM products WHERE product_id = ?";
            $stmtUnit = $conn->prepare($unitQuery);
            if ($stmtUnit === false) {
                throw new Exception("Error preparing unit query: " . $conn->error);
            }
            $stmtUnit->bind_param("i", $productID);
            $stmtUnit->execute();
            $resultUnit = $stmtUnit->get_result();

            if ($resultUnit->num_rows > 0) {
                $unitRow = $resultUnit->fetch_assoc();
                $unitID = intval($unitRow['unit_id']);
            } else {
                throw new Exception("Unit ID not found for product ID $productID.");
            }
            $stmtUnit->close();

            // === INSERT INTO TRANSACTIONS TABLE ===
            $transactionQuery = "INSERT INTO transactions (transaction_type, from_company_id, to_company_id, posting_date, delivery_date, dr_id, po_id, reference_po_id, product_id, quantity, unit_id, created_at)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
            $stmtTransaction = $conn->prepare($transactionQuery);

            $stmtTransaction->bind_param(
                "siiiiiiiiii",
                $transactionType, // Set as 'invoices'
                $fromCompanyID,
                $toCompanyID,
                $postingDate,
                $deliveryDate,
                $drID,
                $poID,
                $referencePoID,
                $productID,
                $quantity,
                $unitID
            );

            if (!$stmtTransaction->execute()) {
                throw new Exception("Error inserting transaction: " . mysqli_error($conn));
            }

            $stmtTransaction->close();
            // === END OF TRANSACTIONS INSERTION ===
        }
        $stmt->close();
    }

    mysqli_commit($conn);

    echo json_encode(['status' => 'success', 'message' => "Transfer updated to $statusName successfully."]);

} catch (Exception $e) {
    mysqli_rollback($conn);
    error_log("update-transfer-status.php - Error: " . $e->getMessage() . "\nData: " . json_encode($_POST));
    echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please check the logs.']);
} finally {
    mysqli_autocommit($conn, TRUE);
    mysqli_close($conn);
}
?>