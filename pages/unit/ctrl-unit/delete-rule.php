<?php
session_start();
include '../../../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rule_id = $_POST['rule_id'];

    if (empty($rule_id)) {
        $_SESSION['error'] = "Invalid rule ID!";
        header("Location: ../unit.php");
        exit();
    }

    $deleteQuery = "DELETE FROM unit_conversions WHERE rule_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $rule_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Rule deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting rule.";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../unit.php");
    exit();
}
?>