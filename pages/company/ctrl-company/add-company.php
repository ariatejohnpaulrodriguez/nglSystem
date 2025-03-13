<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName = trim($_POST['companyName']);
    $companyAddress = trim($_POST['companyAddress']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $email = trim($_POST['email']);
    $plant = trim($_POST['plant']);
    $plant_name = trim($_POST['plantName']);
    $attention = trim($_POST['attention']);

    // File Upload Handling
    $target_dir = "../../../dist/img/companies/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate if file is an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['error'] = "File is not an image.";
        $uploadOk = 0;
    }

    // Allow only specific formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $_SESSION['error'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Move the uploaded file
    if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = "dist/img/companies/" . $image_name; // Store relative path in DB
    } else {
        $image_path = null; // No image uploaded
    }

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO companies (name, address, phone_number, email, plant, plant_name, attention, image) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $companyName, $companyAddress, $phoneNumber, $email, $plant, $plant_name, $attention, $image_path);

    // Execute and check success
    if ($stmt->execute()) {
        $_SESSION['success'] = "Company added successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to add Company.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the company list page with feedback
    header("Location: ../company-list.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../company-list.php");
    exit();
}
?>