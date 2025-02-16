<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $president_id = $_POST['president_id']; // Make sure this is passed from the form
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation (optional but recommended)
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username)) {
        echo "All fields except password must be filled out.";
        exit();
    }

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
        $query = "UPDATE tbl_president 
                  SET president_firstname = ?, 
                      president_lastname = ?, 
                      president_email = ?,  
                      president_username = ?,
                      president_password = ?
                  WHERE president_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $username, $hashedPassword, $president_id);
    } else {
        // If password was not provided, update other fields but leave the password unchanged
        $query = "UPDATE tbl_president 
                  SET president_firstname = ?, 
                      president_lastname = ?, 
                      president_email = ?,  
                      president_username = ?
                  WHERE president_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $username, $president_id);
    }

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../president-list.php"); // Adjust path if necessary
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>