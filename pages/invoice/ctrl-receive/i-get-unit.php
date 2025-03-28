<?php
include '../../../includes/conn.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT unit_id, unit_name FROM units";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $units = [];
    while ($row = $result->fetch_assoc()) {
        $units[] = $row;
    }

    echo json_encode($units);
} catch (Exception $e) {
    error_log("Error fetching units: " . $e->getMessage());
    echo json_encode(["error" => true, "message" => "Error fetching units."]);
} finally {
    $conn->close();
}
?>