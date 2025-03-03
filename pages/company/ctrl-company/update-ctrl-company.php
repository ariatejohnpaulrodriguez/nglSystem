<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $company_id = $_POST['company_id'];
    $name = $_POST['companyName'];
    $address = $_POST['companyAddress'];
    $phoneNumber = $_POST['companyPhoneNumber'];
    $email = $_POST['companyEmail'];
    $plant = $_POST['companyPlant'];
    $plant_name = $_POST['companyPlantname'];
    $attention = $_POST['companyAttention'];

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
            echo "File is not an image.";
            exit();
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
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
            echo "Error uploading file.";
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

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../company-list.php"); // Redirect after successful update
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>