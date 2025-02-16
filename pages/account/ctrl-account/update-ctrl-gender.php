<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $gender_id = $_POST['gender_id']; // Make sure this is passed from the form
    $gender_name = $_POST['genderName'];

    // Basic validation (optional but recommended)
    if (empty($gender_name)) {
        echo "Gender fields must be filled out.";
        exit();
    }

    // Prepare SQL query using prepared statements
    if ($gender_name) {
        $query = "UPDATE genders 
                  SET gender_name = ?
                  WHERE gender_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $gender_name, $gender_id);
    }

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../gender-list.php"); // Adjust path if necessary
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>