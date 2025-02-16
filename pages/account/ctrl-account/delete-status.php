<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status_id'])) {
    $status_id = $_POST['status_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM statuses WHERE status_id = ?");
    $stmt->bind_param("i", $status_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect silently to product-list.php
header("Location: ../status-list.php");
exit();
?>