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

    // File Upload Handling
    $target_dir = "../../../dist/img/companies/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate if file is an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.'); window.history.back();</script>";
        $uploadOk = 0;
    }

    // Allow only specific formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.history.back();</script>";
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