<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session for Toastr notifications

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $employee_id = $_POST['employee_id'];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $currentRole = $_POST['roles'];

    // Check if password is not empty before hashing
    if (!empty($password)) {
        // Hash the password securely using bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    } else {
        // If the password is empty, do not update it
        $hashedPassword = null;
    }

    // Prepare SQL query using prepared statements
    if ($hashedPassword) {
        $query = "UPDATE employees 
                  SET role_id = ?, 
                      first_name = ?, 
                      last_name = ?, 
                      email = ?, 
                      phone_number = ?, 
                      gender_id = ?, 
                      status_id = ?, 
                      username = ?, 
                      password_hash = ? 
                  WHERE employee_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssiissi", $currentRole, $firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $hashedPassword, $employee_id);
    } else {
        // If password is not provided, update all other fields
        $query = "UPDATE employees 
                  SET role_id = ?, 
                      first_name = ?, 
                      last_name = ?, 
                      email = ?, 
                      phone_number = ?, 
                      gender_id = ?, 
                      status_id = ?, 
                      username = ? 
                  WHERE employee_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssssssi", $currentRole, $firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $employee_id);
    }

    // Execute the query and handle feedback
    if ($stmt->execute()) {
        $_SESSION['success'] = "Employee updated successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to update employee details.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to employee list page with Toastr feedback
    header("Location: ../employee-list.php");
    exit();
}
?>