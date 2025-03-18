<?php
session_start();
include '../../../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unit_id = $_POST['unit_id'];

    if (empty($unit_id)) {
        $_SESSION['error'] = "Invalid unit ID!";
        header("Location: ../unit.php");
        exit();
    }

    // Check if unit exists
    $checkQuery = "SELECT * FROM units WHERE unit_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $unit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Unit not found!";
    } else {
        // Delete unit
        $deleteQuery = "DELETE FROM units WHERE unit_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $unit_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Unit deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting unit.";
        }
    }

    $stmt->close();
    $conn->close();
    header("Location: ../unit.php");
    exit();
}
?>