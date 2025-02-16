<?php
include '../../../includes/conn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName = trim($_POST['companyName']);
    $companyAddress = trim($_POST['companyAddress']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $email = trim($_POST['email']);
    $plant = trim($_POST['plant']);
    $plant_name = trim($_POST['plantName']);
    $attention = trim($_POST['attention']);


    $stmt = $conn->prepare("INSERT INTO companies (name, address, phone_number, email, plant, plant_name, attention) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $companyName, $companyAddress, $phoneNumber, $email, $plant, $displayname, $attention);

    // Execute and check success
    if ($stmt->execute()) {
        echo "<script>alert('Company added successfully!'); window.location.href='../company-list.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add Company.'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../company-list.php");
    exit();
}
?>