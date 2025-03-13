<?php
include '../../../includes/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gender_id'])) {
    $gender_id = $_POST['gender_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM genders WHERE gender_id = ?");
    $stmt->bind_param("i", $gender_id);

    // Set feedback messages based on execution
    if ($stmt->execute()) {
        $_SESSION['success'] = "Gender deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to delete gender.";
    }

    $stmt->close();
}

$conn->close();

// Redirect back to gender-list.php
header("Location: ../gender-list.php");
exit();
?>