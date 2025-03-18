<?php
session_start();
include '../../../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unit_name = trim($_POST['unit_name']);

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
        // Insert new unit
        $insertQuery = "INSERT INTO units (unit_name) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("s", $unit_name);

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