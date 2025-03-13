<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

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

    // Basic validation
    if (empty($role) || empty($gender) || empty($status)) {
        $_SESSION['error'] = "Invalid role, gender, or status!";
        header("Location: ../employee.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../employee.php");
        exit();
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
        $_SESSION['error'] = "Invalid role selected!";
        header("Location: ../employee.php");
        exit();
    }
    $stmt->close();

    // Validate gender_id
    $genderQuery = "SELECT gender_id FROM genders WHERE gender_id = ?";
    $stmt = $conn->prepare($genderQuery);
    $stmt->bind_param("i", $gender);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Invalid gender selected!";
        header("Location: ../employee.php");
        exit();
    }
    $stmt->close();

    // Validate status_id
    $statusQuery = "SELECT status_id FROM statuses WHERE status_id = ?";
    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param("i", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Invalid status selected!";
        header("Location: ../employee.php");
        exit();
    }
    $stmt->close();

    // Insert into employees table
    $stmt = $conn->prepare("INSERT INTO employees (role_id, first_name, last_name, email, phone_number, gender_id, status_id, username, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssiiss", $role, $firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Employee added successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to add employee.";
    }

    $stmt->close();
    $conn->close();

    // Redirect to the employee list with Toastr feedback
    header("Location: ../employee-list.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method!";
    header("Location: ../employee.php");
    exit();
}
?>