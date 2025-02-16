<?php
include '../../includes/header.php';
include '../../includes/session.php'
    ?>

<body class="hold-transition sidebar-mini">
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
                            <h1>Create Gender</h1>
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
                                    <h5 class="card-title">Add Gender</h5>
                                </div>
                                <form action="ctrl-account/add-gender.php" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender">Input Gender</label>
                                                <input class="form-control" type="text" id="gender" name="gender"
                                                    placeholder="Ex. Male..." required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center" style="background: transparent;">
                                        <button type="submit" class="btn btn-primary">Gender Submit</button>
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