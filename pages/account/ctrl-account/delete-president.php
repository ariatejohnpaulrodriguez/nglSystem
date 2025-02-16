<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['president_id'])) {
    $president_id = $_POST['president_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM tbl_president WHERE president_id = ?");
    $stmt->bind_param("i", $president_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect silently to product-list.php
header("Location: ../president-list.php");
exit();
?>