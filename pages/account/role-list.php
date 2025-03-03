<?php
include '../../includes/conn.php';
include '../../includes/session.php';
?>

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
                                    <h3 class="card-title">Roles List</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Role ID</th>
                                                <th>Role Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL query to fetch data
                                            $sql = "SELECT role_id, role_name FROM roles";
                                            $result = mysqli_query($conn, $sql);

                                            // Check if there are any rows returned
                                            if (mysqli_num_rows($result) > 0) {
                                                // Loop through the rows and display them in the table
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row["role_id"] . "</td>";
                                                    echo "<td>" . $row["role_name"] . "</td>";
                                                    echo "<td>";
                                                    echo "<form action='update-role.php' method='post' style='display:inline-block; margin-right:5px;'>";
                                                    echo "<input type='hidden' name='role_id' value='" . $row["role_id"] . "'>";
                                                    echo "<input type='submit' value='Edit' class='btn btn-primary'>";
                                                    echo "</form>";
                                                    echo "<form action='ctrl-account/delete-role.php' method='post' style='display:inline-block;' onsubmit='return confirmDelete()'>";
                                                    echo "<input type='hidden' name='role_id' value='" . $row["role_id"] . "'>";
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
                                                return confirm("Are you sure you want to delete this role?");
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
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <?php include '../../includes/script.php'; ?>
</body>

</html>