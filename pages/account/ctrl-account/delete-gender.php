<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gender_id'])) {
    $gender_id = $_POST['gender_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM genders WHERE gender_id = ?");
    $stmt->bind_param("i", $gender_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect silently to product-list.php
header("Location: ../gender-list.php");
exit();
?>