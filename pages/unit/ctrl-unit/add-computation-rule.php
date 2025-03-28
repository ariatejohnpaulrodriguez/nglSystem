<?php
include '../../../includes/conn.php';
include '../../../includes/session.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $base_unit_id = $_POST['base_unit'];
    $target_unit_id = $_POST['target_unit'];
    $operator = $_POST['operator'];
    $transaction_type = $_POST['transaction_type'];

    // Fetch the unit types from the database
    $unitTypeQuery = "SELECT unit_type FROM units WHERE unit_id IN (?, ?)";
    $stmt = $conn->prepare($unitTypeQuery);
    $stmt->bind_param("ii", $base_unit_id, $target_unit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $unit_types = [];

    while ($row = $result->fetch_assoc()) {
        $unit_types[] = $row['unit_type'];
    }

    $stmt->close();

    // Check if both units belong to the same unit type
    if (count($unit_types) == 2 && $unit_types[0] == $unit_types[1]) {
        // Since both units belong to the same type, no conversion is needed
        $requires_conversion = false;
    } else {
        // Different unit types mean conversion is required
        $requires_conversion = true;
    }

    // If conversion is needed and base unit differs from target unit, check for conversion rules
    if ($requires_conversion && $base_unit_id != $target_unit_id) {
        $conversionQuery = "SELECT conversion_factor FROM unit_conversions WHERE base_unit_id = ? AND target_unit_id = ?";
        $stmt = $conn->prepare($conversionQuery);
        $stmt->bind_param("ii", $base_unit_id, $target_unit_id);
        $stmt->execute();
        $conversionResult = $stmt->get_result();

        if ($conversionResult->num_rows == 0) {
            $_SESSION['error'] = "No conversion rule found for these units!";
            header("Location: ../unit.php");
            exit();
        }

        $conversionData = $conversionResult->fetch_assoc();
        $conversion_factor = $conversionData['conversion_factor'];
        $stmt->close();
    } else {
        $conversion_factor = 1; // No conversion needed
    }

    // Define computation formula
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
    $stmt->bind_param("isss", $base_unit_id, $operator, $target_unit_id, $transaction_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Error: This computation rule already exists!";
    } else {
        // Insert new computation rule with formula and conversion factor if needed
        $insertQuery = "INSERT INTO unit_computations (base_unit_id, operation, target_unit_id, transaction_type, formula) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("issss", $base_unit_id, $operator, $target_unit_id, $transaction_type, $formula);

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