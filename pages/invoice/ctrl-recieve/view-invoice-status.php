<?php
include '../../../includes/conn.php';

// Output JSON header
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get invoice_id from GET request, using null coalescing operator
$invoiceId = $_GET['invoice_id'] ?? null;

// Check if invoice ID is valid
if (!is_numeric($invoiceId)) {
    echo json_encode(['error' => 'Invalid invoice ID']);
    exit;
}

try {
    // Prepare the SQL statement
    $sql = "
        SELECT 
            invoices.invoice_id, 
            posting_dates.date_value AS posting_date,
            delivery_dates.date_value AS delivery_date,
            invoices.dr_id, 
            invoices.po_id, 
            invoices.reference_po_id, 
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
            invoice_products.invoice_product_id,
            invoice_products.invoice_id AS product_invoice_id,
            invoice_products.product_id,
            invoice_products.quantity,
            invoice_products.code,
            invoice_products.brand,
            invoice_products.description
        FROM invoices
        INNER JOIN companies AS from_company ON invoices.from_company_id = from_company.company_id
        INNER JOIN companies AS to_company ON invoices.to_company_id = to_company.company_id
        LEFT JOIN delivery_receipts ON invoices.dr_id = delivery_receipts.dr_id
        LEFT JOIN purchase_orders ON invoices.po_id = purchase_orders.po_id
        LEFT JOIN reference_pos ON invoices.reference_po_id = reference_pos.reference_po_id
        LEFT JOIN dates AS posting_dates ON invoices.posting_date = posting_dates.date_id
        LEFT JOIN dates AS delivery_dates ON invoices.delivery_date = delivery_dates.date_id
        LEFT JOIN invoice_products ON invoices.invoice_id = invoice_products.invoice_id
        WHERE invoices.invoice_id = ?
        ORDER BY invoices.invoice_id DESC, invoice_products.invoice_product_id ASC
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Error preparing SQL statement: " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param("i", $invoiceId);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Initialize arrays
        $invoiceData = [
            "invoice" => [],
            "products" => []
        ];

        // Fetch data and populate arrays
        while ($row = $result->fetch_assoc()) {
            // Populate invoice data only once
            if (empty($invoiceData["invoice"])) {
                $invoiceData["invoice"] = [
                    "invoice_id" => $row["invoice_id"],
                    "posting_date" => $row["posting_date"],
                    "delivery_date" => $row["delivery_date"],
                    "dr_number" => $row["dr_number"],
                    "po_number" => $row["po_number"],
                    "reference_po_number" => $row["reference_po_number"],
                    "from_company_name" => $row["from_company_name"],
                    "from_company_address" => $row["from_company_address"],
                    "from_company_phone" => $row["from_company_phone"],
                    "from_company_attention" => $row["from_company_attention"],
                    "to_company_name" => $row["to_company_name"],
                    "to_company_address" => $row["to_company_address"],
                    "to_company_phone" => $row["to_company_phone"],
                    "to_company_attention" => $row["to_company_attention"]
                ];
            }

            // Populate product data
            $invoiceData["products"][] = [
                "invoice_product_id" => $row["invoice_product_id"],
                "product_invoice_id" => $row["product_invoice_id"],
                "product_id" => $row["product_id"],
                "quantity" => $row["quantity"],
                "code" => $row["code"],
                "brand" => $row["brand"],
                "description" => $row["description"]
            ];
        }

        // Return JSON data
        echo json_encode($invoiceData);
    } else {
        // If no data is found, return an error message
        echo json_encode(["error" => "No data found for invoice ID: " . $invoiceId]);
    }

    // Close the statement
    $stmt->close();
} catch (Exception $e) {
    // Handle exceptions
    error_log("view-invoice-status.php - Error: " . $e->getMessage());
    echo json_encode(["error" => "An error occurred while fetching invoice data. Please check the server logs."]);
} finally {
    // Close the connection
    if (isset($conn)) {
        $conn->close();
    }
}
?>