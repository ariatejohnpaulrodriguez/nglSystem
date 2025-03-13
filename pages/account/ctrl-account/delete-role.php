<?php
include '../../../includes/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['role_id'])) {
    $role_id = $_POST['role_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM roles WHERE role_id = ?");
    $stmt->bind_param("i", $role_id);

    // Execute the query and set session messages
    if ($stmt->execute()) {
        $_SESSION['success'] = "Role deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to delete role.";
    }

    $stmt->close();
}

$conn->close();

// Redirect back to role-list.php
header("Location: ../role-list.php");
exit();
?>