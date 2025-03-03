<?php
include '../../includes/conn.php';
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
                                                    echo "<form action='update-employee.php' method='post' style='display:inline-block; margin-right:5px;'>";
                                                    echo "<input type='hidden' name='employee_id' value='" . $row["employee_id"] . "'>";
                                                    echo "<input type='submit' value='Edit' class='btn btn-primary'>";
                                                    echo "</form>";
                                                    echo "<form action='ctrl-account/delete-employee.php' method='post' style='display:inline-block;' onsubmit='return confirmDelete()'>";
                                                    echo "<input type='hidden' name='employee_id' value='" . $row["employee_id"] . "'>";
                                                    echo "<input type='submit' value='Delete' class='btn btn-danger'>";
                                                    echo "</form>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No data found</td></tr>";
                                            }

                                            mysqli_close($conn);
                                            ?>
                                        </tbody>
                                        <!-- JavaScript for Confirmation -->
                                        <script>
                                            function confirmDelete() {
                                                return confirm("Are you sure you want to delete this employee?");
                                            }
                                        </script>
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
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>