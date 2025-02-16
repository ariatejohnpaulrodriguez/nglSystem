<?php
include_once '../../../includes/conn.php'; // Include your database connection file

$response = array('status' => '', 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Debugging output
    error_log("Received POST data: product_id = $product_id, quantity = $quantity");

    // Validate the input
    if (!empty($product_id) && !empty($quantity) && is_numeric($quantity) && $quantity > 0) {
        // Prepare the SQL statement to decrease the product quantity
        $sql = "UPDATE products SET quantity = quantity - ? WHERE product_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param('ii', $quantity, $product_id);

            // Execute the statement
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Quantity updated successfully';
                error_log('Quantity updated successfully for product ID: ' . $product_id);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $conn->error;
                error_log('Error executing statement: ' . $conn->error);
            }

            // Close the statement
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $conn->error;
            error_log('Error preparing statement: ' . $conn->error);
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid input';
        error_log('Invalid input received');
    }
}

// Close the connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>