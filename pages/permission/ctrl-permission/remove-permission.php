<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role_id'], $_POST['permission_id'])) {
    $role_id = $_POST['role_id'];
    $permission_id = $_POST['permission_id'];

    // Delete from rolePermissions table
    $stmt = $conn->prepare("DELETE FROM rolePermissions WHERE role_id = ? AND permission_id = ?");
    $stmt->bind_param("ii", $role_id, $permission_id);

    // Execute query and handle feedback
    if ($stmt->execute()) {
        $_SESSION['success'] = "Permission removed successfully!";
    } else {
        $_SESSION['error'] = "Failed to remove permission.";
    }

    // Redirect to permission list with feedback
    header("Location: ../permission-list.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../permission-list.php");
    exit();
}
?>