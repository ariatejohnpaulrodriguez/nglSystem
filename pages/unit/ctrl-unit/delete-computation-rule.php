<?php
include '../../../includes/conn.php';
include '../../../includes/session.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $rule_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM unit_computations WHERE computation_rule_id = ?");
    $stmt->bind_param("i", $rule_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Computation rule deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting computation rule.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: ../unit.php");
exit();
?>