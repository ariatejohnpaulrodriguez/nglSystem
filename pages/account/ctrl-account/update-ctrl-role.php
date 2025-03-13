<?php
include '../../../includes/conn.php'; // Database connection
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role_id = $_POST['role_id'];
    $role_name = trim($_POST['role_name']);

    // Validate inputs
    if (empty($role_id) || !is_numeric($role_id)) {
        $_SESSION['error'] = "Invalid Role ID.";
        header("Location: ../role-list.php");
        exit();
    }

    if (empty($role_name)) {
        $_SESSION['error'] = "Role name cannot be empty.";
        header("Location: ../role-list.php");
        exit();
    }

    // Prepare SQL query using prepared statements
    $query = "UPDATE roles SET role_name = ? WHERE role_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $role_name, $role_id);

    // Execute query and set session messages
    if ($stmt->execute()) {
        $_SESSION['success'] = "Role updated successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to update role.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to role list
    header("Location: ../role-list.php");
    exit();
}
?>