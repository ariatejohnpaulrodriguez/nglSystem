<?php
include '../../../includes/conn.php';

// Output JSON header
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get transfer_id from GET request, using null coalescing operator
$transferId = $_GET['transfer_id'] ?? null;

// Check if transfer ID is valid
if (!is_numeric($transferId)) {
    echo json_encode(['error' => 'Invalid transfer ID']);
    exit;
}

try {
    // Prepare the SQL statement
    $sql = "
        SELECT 
            transfers.transfer_id, 
            transfers.posting_date AS posting_date,
            transfers.delivery_date AS delivery_date,
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
        LEFT JOIN delivery_receipts ON transfers.dr_id = delivery_receipts.dr_id
        LEFT JOIN purchase_orders ON transfers.po_id = purchase_orders.po_id
        LEFT JOIN reference_pos ON transfers.reference_po_id = reference_pos.reference_po_id
        LEFT JOIN statuses ON transfers.status_id = statuses.status_id
        LEFT JOIN transfer_products ON transfers.transfer_id = transfer_products.transfer_id
        WHERE transfers.transfer_id = ?
        ORDER BY transfers.transfer_id DESC, transfer_products.transfer_product_id ASC
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Error preparing SQL statement: " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param("i", $transferId);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Initialize arrays
        $transferData = [
            "transfer" => [],
            "products" => []
        ];

        // Fetch data and populate arrays
        while ($row = $result->fetch_assoc()) {
            // Populate transfer data only once
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

        // Return JSON data
        echo json_encode($transferData);
    } else {
        // If no data is found, return an error message
        echo json_encode(["error" => "No data found for transfer ID: " . $transferId]);
    }

    // Close the statement
    $stmt->close();
} catch (Exception $e) {
    // Handle exceptions
    error_log("view-transfer-status.php - Error: " . $e->getMessage());
    echo json_encode(["error" => "An error occurred while fetching transfer data. Please check the server logs."]);
} finally {
    // Close the connection
    if (isset($conn)) {
        $conn->close();
    }
}
?>