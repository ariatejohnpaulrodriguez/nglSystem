<?php
include '../../includes/header.php';
include '../../includes/session.php';
?>

<link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../../../dist/css/adminlte.min.css">

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include '../../includes/navbar.php'; ?>
        <!-- /.navbar -->
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Update Account</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="col-md-6">
                            <!-- Form Element sizes -->
                            <div class="card card-secondary">
                                <?php
                                include '../../includes/conn.php';

                                // Initialize variables
                                $employee_id = '';
                                $firstName = '';
                                $lastName = '';
                                $email = '';
                                $phoneNumber = '';
                                $gender = '';
                                $status = '';
                                $username = '';
                                $password = '';
                                $currentRole = '';
                                $currentGender = '';
                                $currentStatuses = '';

                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $employee_id = $_POST['employee_id'];

                                    // Fetch the president data based on the ID
                                    $query = "SELECT first_name, last_name, email, phone_number, gender_id, status_id, username, password_hash, role_id FROM employees WHERE employee_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $employee_id);
                                    $stmt->execute();
                                    $stmt->bind_result($firstName, $lastName, $email, $phoneNumber, $gender, $status, $username, $password, $currentRole);
                                    $stmt->fetch();
                                    $stmt->close();
                                    $conn->close();
                                }
                                ?>
                                <div class="card-header">
                                    <h5 class="card-title">Change Employee Information</h5>
                                </div>
                                <form action="ctrl-account/update-ctrl-employee.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="role_id">Select New Role</label>
                                                    <select class="form-control" id="role" name="role" required>
                                                        <option value="" disabled>-- Select --</option>

                                                        <?php
                                                        include '../../includes/conn.php';
                                                        // Fetch roles from the database
                                                        $rolesQuery = "SELECT role_id, role_name FROM roles";
                                                        $rolesResult = $conn->query($rolesQuery);

                                                        if ($rolesResult->num_rows > 0) {
                                                            while ($row = $rolesResult->fetch_assoc()) {
                                                                $selected = ($row['role_id'] == $currentRole) ? 'selected' : '';
                                                                echo "<option value='" . $row['role_id'] . "' $selected>" . $row['role_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="employee_id">Employee ID</label>
                                                    <input class="form-control" type="text" id="employee_id"
                                                        name="employee_id"
                                                        value="<?php echo htmlspecialchars($employee_id); ?>" required
                                                        readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="firstName">First Name</label>
                                                    <input class="form-control" type="text" id="firstName"
                                                        name="firstName"
                                                        value="<?php echo htmlspecialchars($firstName); ?>"
                                                        placeholder="Enter First Name" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="lastName">Last Name</label>
                                                    <input class="form-control" type="text" id="lastName"
                                                        name="lastName"
                                                        value="<?php echo htmlspecialchars($lastName); ?>"
                                                        placeholder="Enter Last Name" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input class="form-control" type="text" id="email" name="email"
                                                        value="<?php echo htmlspecialchars($email); ?>"
                                                        placeholder="Enter Email" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="phoneNumber">Phone Number</label>
                                                    <input class="form-control" type="text" id="phoneNumber"
                                                        name="phoneNumber"
                                                        value="<?php echo htmlspecialchars($phoneNumber); ?>"
                                                        placeholder="Enter Phone Number" required>
                                                </div>

                                            </div>

                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gender">Gender</label>
                                                    <select class="form-control" id="gender" name="gender" required>
                                                        <option value="" disabled>-- Select --</option>

                                                        <?php
                                                        include '../../includes/conn.php';
                                                        // Fetch roles from the database
                                                        $gendersQuery = "SELECT gender_id, gender_name FROM genders";
                                                        $gendersResult = $conn->query($gendersQuery);

                                                        if ($gendersResult->num_rows > 0) {
                                                            while ($row = $gendersResult->fetch_assoc()) {
                                                                $selected = ($row['gender_id'] == $currentGender) ? 'selected' : '';
                                                                echo "<option value='" . $row['gender_id'] . "' $selected>" . $row['gender_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="" disabled>-- Select --</option>

                                                        <?php
                                                        include '../../includes/conn.php';
                                                        // Fetch roles from the database
                                                        $statusesQuery = "SELECT status_id, status_name FROM statuses";
                                                        $statusesResult = $conn->query($statusesQuery);

                                                        if ($statusesResult->num_rows > 0) {
                                                            while ($row = $statusesResult->fetch_assoc()) {
                                                                $selected = ($row['status_id'] == $currentGender) ? 'selected' : '';
                                                                echo "<option value='" . $row['status_id'] . "' $selected>" . $row['status_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input class="form-control" type="text" id="username"
                                                        name="username"
                                                        value="<?php echo htmlspecialchars($username); ?>"
                                                        placeholder="Enter Username" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="password">New Password</label>
                                                    <input class="form-control" type="password" id="password"
                                                        name="password" placeholder="Enter Password">
                                                </div>

                                                <div class="form-group">
                                                    <label for="confirmPassword">Confirm New Password</label>
                                                    <input class="form-control" type="password" id="confirmPassword"
                                                        name="confirmPassword" placeholder="Confirm Password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include '../../includes/footer.php'; ?>
    </div><!-- /.wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>