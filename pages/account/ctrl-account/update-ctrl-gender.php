<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session for Toastr notifications

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $gender_id = $_POST['gender_id']; // Ensure this is passed from the form
    $gender_name = trim($_POST['genderName']);

    // Basic validation
    if (empty($gender_name)) {
        $_SESSION['error'] = "Gender field must be filled out.";
        header("Location: ../gender-list.php"); // Redirect with error message
        exit();
    }

    // Prepare SQL query using prepared statements
    $query = "UPDATE genders 
              SET gender_name = ?
              WHERE gender_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $gender_name, $gender_id);

    // Execute query
    if ($stmt->execute()) {
        $_SESSION['success'] = "Gender updated successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to update gender.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to gender list with session messages
    header("Location: ../gender-list.php");
    exit();
}
?>