<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $company_id = $_POST['company_id']; // Make sure this is passed from the form
    $name = $_POST['companyName'];
    $address = $_POST['companyAddress'];
    $phoneNumber = $_POST['companyPhoneNumber'];
    $email = $_POST['companyEmail'];
    $plant = $_POST['companyPlant'];
    $plant_name = $_POST['companyPlantname'];
    $attention = $_POST['companyAttention'];

    // Prepare SQL query using prepared statements
    if ($name) {
        $query = "UPDATE companies 
                  SET name = ?, 
                      address = ?, 
                      phone_number = ?,  
                      email = ?,
                      plant = ?,
                      plant_name = ?,
                      attention = ?
                  WHERE company_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $name, $address, $phoneNumber, $email, $plant, $plant_name, $attention, $company_id);
    }

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../company-list.php"); // Adjust path if necessary
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>