<?php
include '../../../includes/conn.php';
include '../../../includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $base_unit_id = $_POST['base_unit_id'];
    $target_unit_id = $_POST['target_unit_id'];
    $conversion_factor = $_POST['conversion_factor'];

    if ($base_unit_id !== $target_unit_id && $conversion_factor > 0) {
        $stmt = $conn->prepare("INSERT INTO unit_rules (base_unit_id, target_unit_id, conversion_factor) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $base_unit_id, $target_unit_id, $conversion_factor);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Conversion rule added successfully.";
        } else {
            $_SESSION['error'] = "Error adding conversion rule.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid data. Ensure different units and positive conversion factor.";
    }
}
header("Location: ../unit.php");
exit();
?>