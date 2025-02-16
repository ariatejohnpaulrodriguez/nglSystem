<?php
include '../../../includes/conn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = trim($_POST['role']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $gender = trim($_POST['gender']);
    $status = trim($_POST['status']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("<script>alert('Passwords do not match!'); window.history.back();</script>");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Get role_id from roles table
    $roleQuery = "SELECT role_id FROM roles WHERE role_name = ?";
    $stmt = $conn->prepare($roleQuery);
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("<script>alert('Invalid role selected!'); window.history.back();</script>");
    }

    $row = $result->fetch_assoc();
    $roleId = $row['role_id'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO employees (role_id, first_name, last_name, email, phone_number, gender_id, status_id, username, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $roleId, $firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $hashedPassword);

    // Execute and check success
    if ($stmt->execute()) {
        echo "<script>alert('Employee added successfully!'); window.location.href='../employee-list.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add employee.'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../employee.php");
    exit();
}
?>