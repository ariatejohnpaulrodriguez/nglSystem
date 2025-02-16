<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $employee_id = $_POST['employee_id']; // Make sure this is passed from the form
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation (optional but recommended)
    //if (empty($firstname) || empty($lastName) || empty($email) || empty($phoneNumber) || empty($gender) || empty($status) | empty($username)) {
    //echo "All fields except password must be filled out.";
    //exit();
    //}

    // Check if password is not empty before hashing
    if (!empty($password)) {
        // Hash the password securely using bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    } else {
        // If the password is empty, do not update the password in the database
        $hashedPassword = null;
    }

    // Prepare SQL query using prepared statements
    if ($hashedPassword) {
        $query = "UPDATE employees 
                  SET first_name = ?, 
                      last_name = ?, 
                      email = ?,  
                      phone_number = ?,
                      gender_id = ?,
                      status_id = ?,
                      username = ?,
                      password = ?
                  WHERE employee_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $hashedPassword, $employee_id);
    } else {
        // If password was not provided, update other fields but leave the password unchanged
        $query = "UPDATE employees 
                  SET first_name = ?, 
                      last_name = ?, 
                      email = ?,
                      phone_number = ?,
                      gender_id = ?,
                      status_id = ?,
                        username = ?
                  WHERE employee_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $employee_id);
    }

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../employee-list.php"); // Adjust path if necessary
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>