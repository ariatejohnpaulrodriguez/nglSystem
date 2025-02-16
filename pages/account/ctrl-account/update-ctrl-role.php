<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $role_id = $_POST['role_id']; // Make sure this is passed from the form
    $role_name = $_POST['role_name'];

    // Basic validation (optional but recommended)
    if (empty($role_name)) {
        echo "Role fields must be filled out.";
        exit();
    }

    // Prepare SQL query using prepared statements
    if ($role_name) {
        $query = "UPDATE roles 
                  SET role_name = ?
                  WHERE role_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $role_name, $role_id);
    }

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../role-list.php"); // Adjust path if necessary
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>