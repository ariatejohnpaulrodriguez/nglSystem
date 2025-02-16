<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['role_id'])) {
    $role_id = $_POST['role_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM roles WHERE role_id = ?");
    $stmt->bind_param("i", $role_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect silently to product-list.php
header("Location: ../role-list.php");
exit();
?>