<?php
include '../../../includes/conn.php';
include '../../../includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rule_id = $_POST['rule_id'];

    $stmt = $conn->prepare("DELETE FROM unit_conversions WHERE rule_id = ?");
    $stmt->bind_param("i", $rule_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Conversion rule deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting conversion rule.";
    }
    $stmt->close();
}
header("Location: ../unit.php");
exit();
?>