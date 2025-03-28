<?php
include '../../includes/header.php';
include '../../includes/session.php';
include '../../includes/conn.php'; // Database connection
include '../../includes/check-permission.php';

// Fetch roles from the database
$roles = [];
$result = $conn->query("SELECT role_id, role_name FROM roles");
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

// Fetch permissions from the database
$permissions = [];
$perm_result = $conn->query("SELECT permission_id, permission_name FROM permissions");
while ($perm_row = $perm_result->fetch_assoc()) {
    $permissions[] = $perm_row;
}
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add Role Permissions</h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Assign Role Permissions Form -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Assign | Add Roles Permission</h3>
                        </div>
                        <div class="card-body">
                            <form action="ctrl-permission/add-permission.php" method="POST">
                                <div class="row">
                                    <!-- Position / Role -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="roleSelect">Position | Role</label>
                                            <select class="form-control" id="roleSelect" name="role_id" required>
                                                <option value="" selected disabled>-- Select Role --</option>
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?= $role['role_id']; ?>">
                                                        <?= $role['role_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Choose Sidebar (Permissions) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sidebarSelect">Choose Sidebar (Hold CTRL to select
                                                multiple)</label>
                                            <select class="form-control" id="sidebarSelect" name="permission_ids[]"
                                                multiple required size="8">
                                                <?php foreach ($permissions as $perm): ?>
                                                    <option value="<?= $perm['permission_id']; ?>">
                                                        <?= $perm['permission_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Apply Button -->
                                <div class="row mt-3">
                                    <div class="col text-center">
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
                    // Fetch roles from the database
                    $roles = [];
                    $result = $conn->query("SELECT role_id, role_name FROM roles");
                    while ($row = $result->fetch_assoc()) {
                        $roles[] = $row;
                    }

                    // Fetch statuses from the database
                    $statuses = [];
                    $status_result = $conn->query("SELECT status_id, status_name FROM statuses");
                    while ($status_row = $status_result->fetch_assoc()) {
                        $statuses[] = $status_row;
                    }
                    ?>

                    <!-- Main content -->
                    <section class="content">
                        <div class="container-fluid">

                            <!-- Assign Approval Permissions Form -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary">
                                    <h3 class="card-title">Assign Approval Permissions</h3>
                                </div>
                                <div class="card-body">
                                    <form action="ctrl-permission/save-status-permissions.php" method="POST">
                                        <div class="row">
                                            <!-- Select Role -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="roleSelect">Select Role</label>
                                                    <select class="form-control" id="roleSelect" name="role_id"
                                                        required>
                                                        <option value="" selected disabled>-- Select Role --</option>
                                                        <?php foreach ($roles as $role): ?>
                                                            <option value="<?= $role['role_id']; ?>">
                                                                <?= $role['role_name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Select Allowed Status -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="statusSelect">Select Allowed Status</label>
                                                    <select class="form-control" id="statusSelect" name="status_ids[]"
                                                        multiple required size="8">
                                                        <?php foreach ($statuses as $status): ?>
                                                            <option value="<?= $status['status_id']; ?>">
                                                                <?= $status['status_name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Apply Button -->
                                        <div class="row mt-3">
                                            <div class="col text-center">
                                                <button type="submit" class="btn btn-primary">Apply</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div><!-- /.container-fluid -->
                    </section>
                </div><!-- /.content-wrapper -->


        </div><!-- /.container-fluid -->
        </section>
    </div><!-- /.content-wrapper -->

    <?php include '../../includes/footer.php'; ?>
    </div><!-- /.wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>