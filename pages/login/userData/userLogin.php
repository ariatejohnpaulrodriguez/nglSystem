<?php
include "../../../includes/conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare SQL statement to fetch user and role information
    $stmt = $conn->prepare("
        SELECT e.*, r.role_name 
        FROM employees e
        JOIN roles r ON e.role_id = r.role_id
        WHERE e.username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['role'] = $row['role_name'];
                $_SESSION['employee_id'] = $row['employee_id'];
                $_SESSION['fullname'] = $row['first_name'] . ' ' . $row['last_name'];

                // Redirect based on role
                //switch ($row['role_name']) {
                //case 'President':
                //header("Location: ../../dashboard/index.php");
                //break;
                //case 'Secretary':
                //header("Location: ../../dashboard/index.php");
                //break;
                // Add more roles and their respective redirects here
                //default:
                //header("Location: ../../dashboard/index.php");
                //break;
                //}
                //exit();
                //} 
                // Redirect based on role
                switch ($row['role_name']) {
                    default:
                        header("Location: ../../dashboard/index.php");
                        break;
                }
                exit();
            } else {
                $_SESSION['login-error'] = 'Incorrect password. Please try again.';
                header("Location: ../login.php");
                exit();
            }
        }
    } else {
        $_SESSION['login-error'] = 'No user found with that username.';
        header("Location: ../login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>