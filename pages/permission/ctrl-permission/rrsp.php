<?php
include '../../../includes/session.php';
include '../../../includes/conn.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['role_id'], $_POST['status_id'])) {
        $role_id = $_POST['role_id'];
        $status_id = $_POST['status_id'];

        // Count remaining statuses for the role
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM role_status_permissions WHERE role_id = ?");
        $check_stmt->bind_param("i", $role_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        // Delete the selected status
        $stmt = $conn->prepare("DELETE FROM role_status_permissions WHERE role_id = ? AND status_id = ?");
        $stmt->bind_param("ii", $role_id, $status_id);
        $stmt->execute();
        $stmt->close();

        // If it was the last status, remove the role from role_status_permissions
        if ($count == 1) {
            $delete_role_stmt = $conn->prepare("DELETE FROM role_status_permissions WHERE role_id = ?");
            $delete_role_stmt->bind_param("i", $role_id);
            $delete_role_stmt->execute();
            $delete_role_stmt->close();
        }

        $_SESSION['success'] = "Status permission removed successfully.";
    } else {
        $_SESSION['error'] = "Invalid request parameters.";
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
}

// Redirect back to the permission list
header("Location: ../permission-list.php");
exit();
?>