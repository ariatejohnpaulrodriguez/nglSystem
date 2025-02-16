<?php
include '../../../includes/conn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("<script>alert('Passwords do not match!'); window.history.back();</script>");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO tbl_president (president_firstname, president_lastname, president_email, president_username, president_password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $hashedPassword);

    // Execute and check success
    if ($stmt->execute()) {
        echo "<script>alert('President added successfully!'); window.location.href='../president-list.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add president.'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../president-list.php");
    exit();
}
?>