<?php
include '../../includes/header.php';
include '../../includes/session.php'
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
                            <h1>Create Roles</h1>
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
                            <div class="card card-secondary shadow">
                                <div class="card-header">
                                    <h5 class="card-title">Add Roles</h5>
                                </div>
                                <form action="ctrl-account/add-role.php" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role">Input Roles</label>
                                                <input class="form-control" type="text" id="role" name="role"
                                                    placeholder="Ex. President..." required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center" style="background: transparent;">
                                        <button type="submit" class="btn btn-primary">Role Submit</button>
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