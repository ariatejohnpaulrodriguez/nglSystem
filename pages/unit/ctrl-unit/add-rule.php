<?php
session_start();
include '../../../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $base_unit_id = $_POST['base_unit_id'];
    $target_unit_id = $_POST['target_unit_id'];
    $conversion_factor = $_POST['conversion_factor'];

    if ($base_unit_id == $target_unit_id) {
        $_SESSION['error'] = "Base and Target units cannot be the same!";
        header("Location: ../unit.php");
        exit();
    }

    // Check if rule already exists
    $checkQuery = "SELECT * FROM unit_conversions WHERE base_unit_id = ? AND target_unit_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $base_unit_id, $target_unit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Conversion rule already exists!";
    } else {
        // Insert new conversion rule
        $insertQuery = "INSERT INTO unit_conversions (base_unit_id, target_unit_id, conversion_factor) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iid", $base_unit_id, $target_unit_id, $conversion_factor);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Conversion rule added successfully!";
        } else {
            $_SESSION['error'] = "Error adding conversion rule.";
        }
    }

    $stmt->close();
    $conn->close();
    header("Location: ../unit.php");
    exit();
}
?>