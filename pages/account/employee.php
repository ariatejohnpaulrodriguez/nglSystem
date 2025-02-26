<?php
include '../../includes/header.php';
include '../../includes/session.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Create Account</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-7 mb-3">
                            <!-- Form Element sizes -->
                            <div class="card card-secondary shadow">
                                <div class="card-header">
                                    <h5 class="card-title">Add Employee Account</h5>
                                </div>
                                <?php
                                // Fetch roles from the database
                                $rolesQuery = "SELECT role_id, role_name FROM roles";
                                $rolesResult = $conn->query($rolesQuery);

                                // Fetch genders from the database
                                $gendersQuery = "SELECT gender_id, gender_name FROM genders";
                                $gendersResult = $conn->query($gendersQuery);

                                // Fetch statuses from the database
                                $statusesQuery = "SELECT status_id, status_name FROM statuses";
                                $statusesResult = $conn->query($statusesQuery);
                                ?>
                                <form action="ctrl-account/add-employee.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <!-- Role Dropdown -->
                                                <div class="form-group">
                                                    <label for="role">Select Role</label>
                                                    <select class="form-control" id="role" name="role" required>
                                                        <option value="" disabled selected>-- Select --</option>
                                                        <?php
                                                        if ($rolesResult->num_rows > 0) {
                                                            while ($row = $rolesResult->fetch_assoc()) {
                                                                echo "<option value='" . $row['role_id'] . "'>" . $row['role_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="firstName">First Name</label>
                                                    <input class="form-control" type="text" id="firstName"
                                                        name="firstName" placeholder="Enter First Name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lastName">Last Name</label>
                                                    <input class="form-control" type="text" id="lastName"
                                                        name="lastName" placeholder="Enter Last Name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input class="form-control" type="email" id="email" name="email"
                                                        placeholder="Enter Email" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phoneNumber">Phone Number (Optional)</label>
                                                    <input class="form-control" type="text" id="phoneNumber"
                                                        name="phoneNumber" placeholder="Enter Phone Number">
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gender">Gender</label>
                                                    <select class="form-control" id="gender" name="gender" required>
                                                        <option value="" disabled selected>-- Select --</option>
                                                        <?php
                                                        if ($gendersResult->num_rows > 0) {
                                                            while ($row = $gendersResult->fetch_assoc()) {
                                                                echo "<option value='" . $row['gender_id'] . "'>" . $row['gender_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="" disabled selected>-- Select --</option>
                                                        <?php
                                                        if ($statusesResult->num_rows > 0) {
                                                            while ($row = $statusesResult->fetch_assoc()) {
                                                                echo "<option value='" . $row['status_id'] . "'>" . $row['status_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input class="form-control" type="text" id="username"
                                                        name="username" placeholder="Enter Username" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input class="form-control" type="password" id="password"
                                                        name="password" placeholder="Enter Password" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirmPassword">Confirm Password</label>
                                                    <input class="form-control" type="password" id="confirmPassword"
                                                        name="confirmPassword" placeholder="Confirm Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center" style="background-color: transparent;">
                                            <button type="submit" class="btn btn-primary">Sign Up</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div><!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>
    </div><!-- /.wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>