<?php
include '../../../includes/conn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = trim($_POST['role']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $gender = trim($_POST['gender']); // Gender ID from form
    $status = trim($_POST['status']); // Status ID from form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Debugging: Check if correct values are being received
    if (empty($role) || empty($gender) || empty($status)) {
        die("<script>alert('Invalid role, gender, or status!'); window.history.back();</script>");
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("<script>alert('Passwords do not match!'); window.history.back();</script>");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Validate role_id
    $roleQuery = "SELECT role_id FROM roles WHERE role_id = ?";
    $stmt = $conn->prepare($roleQuery);
    $stmt->bind_param("i", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("<script>alert('Invalid role selected!'); window.history.back();</script>");
    }
    $stmt->close();

    // Validate gender_id
    $genderQuery = "SELECT gender_id FROM genders WHERE gender_id = ?";
    $stmt = $conn->prepare($genderQuery);
    $stmt->bind_param("i", $gender);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("<script>alert('Invalid gender selected!'); window.history.back();</script>");
    }
    $stmt->close();

    // Validate status_id
    $statusQuery = "SELECT status_id FROM statuses WHERE status_id = ?";
    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param("i", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("<script>alert('Invalid status selected!'); window.history.back();</script>");
    }
    $stmt->close();

    // Insert into employees2 table
    $stmt = $conn->prepare("INSERT INTO employees (role_id, first_name, last_name, email, phone_number, gender_id, status_id, username, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssiiss", $role, $firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Employee added successfully!'); window.location.href='../employee-list.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add employee.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../employee.php");
    exit();
}
?>