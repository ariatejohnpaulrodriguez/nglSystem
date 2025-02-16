<?php
include 'conn.php';
session_start();

// Check if the user is logged in (if the session has a role)
if (!empty($_SESSION['employee_id'])) {
    $employee_id = $_SESSION['employee_id'];  // Get the employee ID from session

    // Fetch employee details along with their role from the employees and roles tables
    $stmt = $conn->prepare("SELECT e.*, r.role_name 
                            FROM employees e
                            JOIN roles r ON e.role_id = r.role_id
                            WHERE e.employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returns any result
    if ($result->num_rows > 0) {
        $row_user = $result->fetch_array();

        // Set session variables for user information
        $_SESSION['fullname'] = $row_user['first_name'] . " " . $row_user['last_name'];
        $_SESSION['email'] = $row_user['email'];
        $_SESSION['role'] = $row_user['role_name'];  // Store role in session
        $_SESSION['role_id'] = $row_user['role_id'];  // Store role ID for any future use
    } else {
        // If no employee found with this ID, log them out and redirect to login
        session_destroy();
        header("location: ../../pages/login/login.php");
        exit();
    }
} else {
    // If no employee_id in session, user is not logged in, redirect to login page
    header("location: ../../pages/login/login.php");
    exit();
}
?>