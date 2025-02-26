<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'] ?? null;
    $product_id = $_POST['product_id'];
    $date_value = $_POST['date'] ?? null;
    $begBalance = $_POST['begBalance'];
    $endBalance = $_POST['endBalance'];
    $quantity = $_POST['quantity'];
    $productType = $_POST['productType'] ?? null;
    $checkedBy = $_POST['checkedBy'];
    $approvedBy = $_POST['approvedBy'];
    $status_id = $_POST['status_id'] ?? null;
    $note = $_POST['note'] ?? null;

    // Insert the selected date into the dates table and get the date_id
    if ($date_value) {
        $query = "INSERT INTO dates (date_value) VALUES (?) ON DUPLICATE KEY UPDATE date_id = LAST_INSERT_ID(date_id)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $date_value);
        $stmt->execute();
        $date_id = $stmt->insert_id;
        $stmt->close();
    } else {
        $date_id = null;
    }

    // Insert or update the inventory record
    $query = "INSERT INTO inventories (product_id, date_id, beg_balance, end_balance, quantity, product_type, checked_by, approved_by, status_id, notes)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE
                  beg_balance = VALUES(beg_balance),
                  end_balance = VALUES(end_balance),
                  quantity = VALUES(quantity),
                  product_type = VALUES(product_type),
                  checked_by = VALUES(checked_by),
                  approved_by = VALUES(approved_by),
                  status_id = VALUES(status_id),
                  notes = VALUES(notes)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiddississ", $product_id, $date_id, $begBalance, $endBalance, $quantity, $productType, $checkedBy, $approvedBy, $status_id, $note);
    $stmt->execute();
    $stmt->close();

    echo "Inventory record updated successfully!";
}
?>