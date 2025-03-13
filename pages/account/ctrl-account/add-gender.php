<?php
include '../../../includes/conn.php'; // Include database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = trim($_POST['gender']);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO genders (gender_name) VALUES (?)");
    $stmt->bind_param("s", $gender);

    // Execute and set session messages
    if ($stmt->execute()) {
        $_SESSION['success'] = "Gender added successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to add Gender.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the gender page
    header("Location: ../gender.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../add-gender.php");
    exit();
}
?>