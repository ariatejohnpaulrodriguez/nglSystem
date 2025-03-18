<?php
include '../../../includes/conn.php';
include '../../../includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $base_unit = $_POST['base_unit'];
    $operator = $_POST['operator'];
    $target_unit = $_POST['target_unit'];
    $transaction_type = $_POST['transaction_type'];

    // Define computation logic
    switch ($operator) {
        case "+":
            $formula = "base_value + target_value";
            break;
        case "-":
            $formula = "base_value - target_value";
            break;
        case "*":
            $formula = "base_value * target_value";
            break;
        case "/":
            $formula = "base_value / target_value";
            break;
        default:
            $formula = "base_value"; // Default if no valid operator
    }

    // Prevent duplicate computation rules
    $checkQuery = "SELECT * FROM unit_computations 
                   WHERE base_unit_id = ? 
                   AND operation = ? 
                   AND target_unit_id = ? 
                   AND transaction_type = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("isss", $base_unit, $operator, $target_unit, $transaction_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Error: This computation rule already exists!";
    } else {
        // Insert new computation rule with formula
        $insertQuery = "INSERT INTO unit_computations (base_unit_id, operation, target_unit_id, transaction_type, formula) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("issss", $base_unit, $operator, $target_unit, $transaction_type, $formula);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Computation rule added successfully.";
        } else {
            $_SESSION['error'] = "Error adding computation rule.";
        }
    }
    $stmt->close();
}
header("Location: ../unit.php");
exit();

?>