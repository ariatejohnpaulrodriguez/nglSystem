<?php
include '../../includes/header.php';
include '../../includes/session.php';
include '../../includes/conn.php'; // Database connection
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
                            <h1>Manage Role Permissions</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Role Permissions List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Position | Role</th>
                                            <th>Allowed Sidebars</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query to fetch roles with their associated permissions
                                        $query = "
                                            SELECT r.role_id, r.role_name, p.permission_id, p.permission_name
                                            FROM rolePermissions rp
                                            JOIN roles r ON rp.role_id = r.role_id
                                            JOIN permissions p ON rp.permission_id = p.permission_id
                                            ORDER BY r.role_name, p.permission_name
                                        ";

                                        $result = $conn->query($query);

                                        if ($result->num_rows > 0) {
                                            $current_role = null;

                                            while ($row = $result->fetch_assoc()) {
                                                if ($current_role !== $row['role_name']) {
                                                    // Print new role row
                                                    if ($current_role !== null) {
                                                        echo "</td></tr>";
                                                    }
                                                    echo "<tr><td>{$row['role_name']}</td><td>";
                                                    $current_role = $row['role_name'];
                                                }

                                                // Display each permission with a remove button
                                                echo "{$row['permission_name']}
                                                    <form action='ctrl-permission/remove-permission.php' method='POST' style='display:inline;'>
                                                        <input type='hidden' name='role_id' value='{$row['role_id']}'>
                                                        <input type='hidden' name='permission_id' value='{$row['permission_id']}'>
                                                        <button type='button' class='btn btn-danger btn-sm' style='padding: 2px 5px; font-size: 12px;' onclick='deletePermissionModal({$row["role_id"]}, {$row["permission_id"]})'>Remove</button>
                                                    </form>
                                                    <br>";
                                            }
                                            echo "</td></tr>"; // Close last row
                                        } else {
                                            echo "<tr><td colspan='2' class='text-center'>No data available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="permissionDeleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
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
                            Are you sure you want to delete this permission? This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <form id="deleteForm" action="ctrl-permission/remove-permission.php" method="post">
                                <input type="hidden" name="role_id" id="role_id_to_delete">
                                <input type="hidden" name="permission_id" id="permission_id_to_delete">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.content-wrapper -->

        <?php include '../../includes/footer.php'; ?>
    </div><!-- /.wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>