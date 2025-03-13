<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $company_id = $_POST['company_id'];
    $name = trim($_POST['companyName']);
    $address = trim($_POST['companyAddress']);
    $phoneNumber = trim($_POST['companyPhoneNumber']);
    $email = trim($_POST['companyEmail']);
    $plant = trim($_POST['companyPlant']);
    $plant_name = trim($_POST['companyPlantname']);
    $attention = trim($_POST['companyAttention']);

    // Fetch the current image path before updating
    $query = "SELECT image FROM companies WHERE company_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $stmt->bind_result($old_image);
    $stmt->fetch();
    $stmt->close();

    // Initialize base SQL Query
    $query = "UPDATE companies 
              SET name = ?, 
                  address = ?, 
                  phone_number = ?,  
                  email = ?,
                  plant = ?,
                  plant_name = ?,
                  attention = ?";

    $params = [$name, $address, $phoneNumber, $email, $plant, $plant_name, $attention];
    $types = "sssssss"; // 7 strings initially

    // Check if a new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../../dist/img/companies/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $target_file = $target_dir . $image_name; // Unique file name

        // Validate image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = "File is not an image.";
            header("Location: ../company-list.php");
            exit();
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: ../company-list.php");
            exit();
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = "dist/img/companies/" . $image_name; // Relative path for database

            // Delete the old image if it exists
            if (!empty($old_image) && file_exists("../../../" . $old_image)) {
                unlink("../../../" . $old_image);
            }

            // Append image update to query
            $query .= ", image = ?";
            $params[] = $image_path;
            $types .= "s"; // Add one more string parameter
        } else {
            $_SESSION['error'] = "Error uploading file.";
            header("Location: ../company-list.php");
            exit();
        }
    }

    // Append the WHERE clause
    $query .= " WHERE company_id = ?";
    $params[] = $company_id;
    $types .= "i"; // Company ID is an integer

    // Prepare SQL statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    // Execute query and handle feedback
    if ($stmt->execute()) {
        $_SESSION['success'] = "Company updated successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to update company. " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the company list page with feedback
    header("Location: ../company-list.php");
    exit();
}
?>