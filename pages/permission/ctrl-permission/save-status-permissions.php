<?php
include '../../../includes/session.php';
include '../../../includes/conn.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role_id = $_POST['role_id']; // Selected Role
    $status_ids = $_POST['status_ids']; // Array of selected Status IDs

    if (!empty($role_id) && !empty($status_ids)) {
        $conn->begin_transaction(); // Start transaction

        try {
            // 1. Get existing status permissions for the role
            $existing_statuses = [];
            $query = "SELECT status_id FROM role_status_permissions WHERE role_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $role_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $existing_statuses[] = $row['status_id'];
            }
            $stmt->close();

            // 2. Determine which statuses to add
            $statuses_to_add = array_diff($status_ids, $existing_statuses);

            // 3. Add only newly selected statuses
            if (!empty($statuses_to_add)) {
                $insert_query = "INSERT INTO role_status_permissions (role_id, status_id) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_query);
                foreach ($statuses_to_add as $status_id) {
                    $stmt->bind_param("ii", $role_id, $status_id);
                    $stmt->execute();
                }
                $stmt->close();
            }

            // 4. Commit transaction
            $conn->commit();
            $_SESSION['success'] = "Approval permissions updated successfully!";
        } catch (Exception $e) {
            $conn->rollback(); // Rollback on error
            $_SESSION['error'] = "Error updating permissions: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Please select a role and at least one status.";
    }
}

// Redirect back
header("Location: ../add-role-permission.php");
exit();
?>