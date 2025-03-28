<?php

if (!isset($_SESSION['employee_id'])) {
    header("Location: ../../pages/error/access-denied.php");
    exit();
}

// Get the requested page
$current_page = basename($_SERVER['PHP_SELF']);

// Extract allowed permissions from session
$allowed_pages = [];
if (isset($_SESSION['permissions'])) {
    foreach ($_SESSION['permissions'] as $permission) {
        if (isset($permission['sub_menu'])) {
            foreach ($permission['sub_menu'] as $sub) {
                $allowed_pages[] = basename($sub['url']);
            }
        }
    }
}

// Check if the user has access to this page
if (!in_array($current_page, $allowed_pages)) {
    header("Location: ../../pages/error/access-denied.php"); // Redirect to an error page
    exit();
}
?>