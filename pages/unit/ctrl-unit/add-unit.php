<?php
session_start();
include '../../../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unit_name = trim($_POST['unit_name']);
    $unit_type = $_POST['unit_type'];

    if (empty($unit_name)) {
        $_SESSION['error'] = "Unit name cannot be empty!";
        header("Location: ../unit.php");
        exit();
    }

    // Check if unit already exists
    $checkQuery = "SELECT * FROM units WHERE unit_name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $unit_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Unit already exists!";
    } else {
        // Insert new unit with unit_type
        $insertQuery = "INSERT INTO units (unit_name, unit_type) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $unit_name, $unit_type);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Unit added successfully!";
        } else {
            $_SESSION['error'] = "Error adding unit.";
        }
    }

    $stmt->close();
    $conn->close();
    header("Location: ../unit.php"); // Redirect back to the form
    exit();
}
?>