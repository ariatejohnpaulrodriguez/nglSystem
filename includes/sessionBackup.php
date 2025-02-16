<?php include 'conn.php';
session_start();
if (!empty($_SESSION['role'])) {
    if ($_SESSION['role'] == "Master key") {
        $employee_id = $_SESSION['employee_id'];
        $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $user = $stmt->get_result();
        $row_user = $user->fetch_array();
        $fullname = $row_user['first_name'] . " " . $row_user['last_name'];
        $email = isset($row_user['email']) ? $row_user['email'] : '';
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        if (!$employee_id) {
            session_destroy();
            header("location: ../../pages/login/login.php");
            exit();
        }
    } elseif ($_SESSION['role'] == "President") {
        $president_id = $_SESSION['president_id'];
        $stmt = $conn->prepare("SELECT * FROM tbl_president WHERE president_id = ?");
        $stmt->bind_param("i", $president_id);
        $stmt->execute();
        $user = $stmt->get_result();
        $row_user = $user->fetch_array();
        $fullname = $row_user['president_firstname'] . " " . $row_user['president_lastname'];
        $email = isset($row_user['email']) ? $row_user['email'] : '';
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        if (!$president_id) {
            session_destroy();
            header("location: ../../pages/login/login.php");
            exit();
        }
    } elseif ($_SESSION['role'] == "Secretary") {
        $employee_id = $_SESSION['employee_id'];
        $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $user = $stmt->get_result();
        $row_user = $user->fetch_array();
        $fullname = $row_user['first_name'] . " " . $row_user['last_name'];
        $email = isset($row_user['email']) ? $row_user['email'] : '';
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        if (!$employee_id) {
            session_destroy();
            header("location: ../../pages/login/login.php");
            exit();
        }
    }
} else {
    header("location: ../../pages/dashboard/index.php");
    exit();
} ?>