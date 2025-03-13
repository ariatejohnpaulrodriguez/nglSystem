<?php
include '../../../includes/conn.php';
include '../../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];
    $permission_ids = $_POST['permission_ids']; // Array of selected permission IDs

    if (!empty($role_id) && !empty($permission_ids)) {
        foreach ($permission_ids as $permission_id) {
            $stmt = $conn->prepare("INSERT INTO rolePermissions (role_id, permission_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $role_id, $permission_id);
            $stmt->execute();
        }
        $_SESSION['success'] = "Permissions assigned successfully.";
    } else {
        $_SESSION['error'] = "Please select a role and at least one permission.";
    }

    header("Location: ../add-role-permission.php");
    exit();
}
?>