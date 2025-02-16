<?php
include '../../../includes/conn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = trim($_POST['gender']);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO genders (gender_name) VALUES (?)");
    $stmt->bind_param("s", $gender);

    // Execute and check success
    if ($stmt->execute()) {
        echo "<script>alert('Gender added successfully!'); window.location.href='../gender.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add Gender.'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../add-gender.php");
    exit();
}
?>