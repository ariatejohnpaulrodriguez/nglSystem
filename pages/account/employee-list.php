<?php
include '../../includes/conn.php';
include '../../includes/check-permission.php';
?>

<?php include '../../includes/session.php'; ?>


<?php
include '../../includes/header.php';
?>

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
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Employee Lists</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Role Name</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Gender</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL query to fetch data
                                            $sql = "
                                            SELECT e.employee_id, r.role_name, e.first_name, e.last_name, e.email, e.phone_number, g.gender_name, s.status_name
                                            FROM employees e
                                            JOIN roles r ON e.role_id = r.role_id
                                            JOIN genders g ON e.gender_id = g.gender_id
                                            JOIN statuses s ON e.status_id = s.status_id
                                            ";
                                            $result = mysqli_query($conn, $sql);

                                            // Check if there are any rows returned
                                            if (mysqli_num_rows($result) > 0) {
                                                // Loop through the rows and display them in the table
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row["employee_id"] . "</td>";
                                                    echo "<td>" . $row["role_name"] . "</td>";
                                                    echo "<td>" . $row["first_name"] . "</td>";
                                                    echo "<td>" . $row["last_name"] . "</td>";
                                                    echo "<td>" . $row["email"] . "</td>";
                                                    echo "<td>" . $row["phone_number"] . "</td>";
                                                    echo "<td>" . $row["gender_name"] . "</td>";
                                                    echo "<td>" . $row["status_name"] . "</td>";
                                                    echo "<td>";

                                                    // Super Admin: Can edit everyone, delete everyone
                                                    if (isset($_SESSION['role']) && $_SESSION['role'] == 'Super Admin') {
                                                        echo "<form action='update-employee.php' method='post' style='display:inline-block; margin-right:5px; margin-bottom:5px;'>";
                                                        echo "<input type='hidden' name='employee_id' value='" . $row["employee_id"] . "'>";
                                                        echo "<input type='submit' value='Edit' class='btn btn-primary btn-sm'>"; // Super Admin gets "Edit"
                                                        echo "</form>";

                                                        echo "<form action='ctrl-account/delete-employee.php' method='post' style='display:inline-block; onsubmit='return confirmDelete()'>";
                                                        echo "<input type='hidden' name='employee_id' value='" . $row["employee_id"] . "'>";
                                                        echo "<button type='button' class='btn btn-danger btn-sm' onclick='deleteEmployeeModal(" . $row["employee_id"] . ")'>Delete</button>";
                                                        echo "</form>";
                                                    } else {
                                                        // Regular users: Can view all, edit only themselves
                                                        echo "<form action='update-employee.php' method='post' style='display:inline-block; margin-right:5px; margin-bottom:5px;'>";
                                                        echo "<input type='hidden' name='employee_id' value='" . $row["employee_id"] . "'>";
                                                        if ($_SESSION['employee_id'] == $row["employee_id"]) {
                                                            echo "<input type='submit' value='Edit' class='btn btn-primary'>"; // Edit only themselves
                                                        }
                                                        echo "</form>";
                                                    }

                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No data found</td></tr>";
                                            }

                                            mysqli_close($conn);
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="employeeDeleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this employee? This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <form id="deleteForm" action="ctrl-account/delete-employee.php" method="post">
                                <input type="hidden" name="employee_id" id="employee_id_to_delete">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>