<?php
include '../../../includes/conn.php';
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get transfer_id from GET request
$transferId = $_GET['transfer_id'] ?? null;
$roleId = $_SESSION['role_id'] ?? null; // Get role ID from session

// Validate transfer ID
if (!is_numeric($transferId)) {
    echo json_encode(['error' => 'Invalid transfer ID']);
    exit;
}

try {
    // Fetch allowed statuses for the role
    $statusQuery = "SELECT status_id FROM role_status_permissions WHERE role_id = ?";
    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param("i", $roleId);
    $stmt->execute();
    $statusResult = $stmt->get_result();

    $allowedStatuses = [];
    while ($row = $statusResult->fetch_assoc()) {
        $allowedStatuses[] = $row['status_id'];
    }

    // If no allowed statuses are set, allow access to all transfers
    if (empty($allowedStatuses)) {
        $statusCondition = "1 = 1"; // No restriction on status
        $params = [$transferId];
        $types = "i";
    } else {
        $placeholders = implode(',', array_fill(0, count($allowedStatuses), '?'));
        $statusCondition = "transfers.status_id IN ($placeholders)";
        $params = array_merge([$transferId], $allowedStatuses);
        $types = str_repeat('i', count($allowedStatuses) + 1);
    }

    // Prepare the SQL query
    $sql = "
    SELECT 
        transfers.transfer_id, 
        pd.date_value AS posting_date,
        dd.date_value AS delivery_date,
        transfers.dr_id, 
        transfers.po_id, 
        transfers.reference_po_id, 
        from_company.name AS from_company_name, 
        from_company.address AS from_company_address, 
        from_company.phone_number AS from_company_phone, 
        from_company.attention AS from_company_attention,
        to_company.name AS to_company_name, 
        to_company.address AS to_company_address, 
        to_company.phone_number AS to_company_phone, 
        to_company.attention AS to_company_attention,
        delivery_receipts.dr_number AS dr_number,
        purchase_orders.po_number AS po_number,
        reference_pos.reference_po AS reference_po_number,
        statuses.status_name,
        transfer_products.transfer_product_id,
        transfer_products.transfer_id AS product_transfer_id,
        transfer_products.product_id,
        transfer_products.quantity,
        transfer_products.code,
        transfer_products.brand,
        transfer_products.description
    FROM transfers
    INNER JOIN companies AS from_company ON transfers.from_company_id = from_company.company_id
    INNER JOIN companies AS to_company ON transfers.to_company_id = to_company.company_id
    LEFT JOIN dates AS pd ON transfers.posting_date = pd.date_id
    LEFT JOIN dates AS dd ON transfers.delivery_date = dd.date_id
    LEFT JOIN delivery_receipts ON transfers.dr_id = delivery_receipts.dr_id
    LEFT JOIN purchase_orders ON transfers.po_id = purchase_orders.po_id
    LEFT JOIN reference_pos ON transfers.reference_po_id = reference_pos.reference_po_id
    LEFT JOIN statuses ON transfers.status_id = statuses.status_id
    LEFT JOIN transfer_products ON transfers.transfer_id = transfer_products.transfer_id
    WHERE transfers.transfer_id = ? AND $statusCondition
    ORDER BY transfers.transfer_id DESC, transfer_products.transfer_product_id ASC";

    // Bind parameters dynamically
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Initialize arrays
        $transferData = ["transfer" => [], "products" => []];

        while ($row = $result->fetch_assoc()) {
            if (empty($transferData["transfer"])) {
                $transferData["transfer"] = [
                    "transfer_id" => htmlspecialchars($row["transfer_id"]),
                    "posting_date" => htmlspecialchars($row["posting_date"]),
                    "delivery_date" => htmlspecialchars($row["delivery_date"]),
                    "dr_number" => htmlspecialchars($row["dr_number"]),
                    "po_number" => htmlspecialchars($row["po_number"]),
                    "reference_po_number" => htmlspecialchars($row["reference_po_number"]),
                    "from_company_name" => htmlspecialchars($row["from_company_name"]),
                    "from_company_address" => htmlspecialchars($row["from_company_address"]),
                    "from_company_phone" => htmlspecialchars($row["from_company_phone"]),
                    "from_company_attention" => htmlspecialchars($row["from_company_attention"]),
                    "to_company_name" => htmlspecialchars($row["to_company_name"]),
                    "to_company_address" => htmlspecialchars($row["to_company_address"]),
                    "to_company_phone" => htmlspecialchars($row["to_company_phone"]),
                    "to_company_attention" => htmlspecialchars($row["to_company_attention"]),
                    "status_name" => htmlspecialchars($row["status_name"])
                ];
            }

            // Populate product data
            $transferData["products"][] = [
                "transfer_product_id" => htmlspecialchars($row["transfer_product_id"]),
                "product_transfer_id" => htmlspecialchars($row["product_transfer_id"]),
                "product_id" => htmlspecialchars($row["product_id"]),
                "quantity" => htmlspecialchars($row["quantity"]),
                "code" => htmlspecialchars($row["code"]),
                "brand" => htmlspecialchars($row["brand"]),
                "description" => htmlspecialchars($row["description"])
            ];
        }

        echo json_encode($transferData);
    } else {
        echo json_encode(["error" => "No data found for transfer ID: " . $transferId]);
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("view-transfer-status.php - Error: " . $e->getMessage());
    echo json_encode(["error" => "An error occurred while fetching transfer data."]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>