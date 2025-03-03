<!DOCTYPE html>
<html lang="en">

<?php
include '../../includes/header.php';
include '../../includes/session.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Site wrapper -->
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
                            <h1>New Registered Company</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Company Form</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" title="Collapse">
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form action="ctrl-company/add-company.php" method="POST"
                                        enctype="multipart/form-data">
                                        <div class=" form-group">
                                            <label for="image">Upload Company Image</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image" name="image"
                                                    required>
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="companyName">Company Name:</label>
                                            <input type="text" id="companyName" class="form-control" name="companyName">
                                        </div>
                                        <div class="form-group">
                                            <label for="companyAddress">Company Address:</label>
                                            <textarea id="companyAddress" class="form-control" rows="4"
                                                name="companyAddress"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="attention">Attention:</label>
                                            <input id="attention" class="form-control" name="attention"
                                                type="text"></input>
                                        </div>
                                        <div class="form-group">
                                            <label for="plant">Plant:</label>
                                            <input id="plant" class="form-control" name="plant" type="text"></input>
                                        </div>
                                        <div class="form-group">
                                            <label for="plantName">Plant Name:</label>
                                            <input id="plantName" class="form-control" name="plantName"
                                                type="text"></input>
                                        </div>
                                        <div class="form-group">
                                            <label for="phoneNumber">Company Phone Number:</label>
                                            <input id="phoneNumber" class="form-control" name="phoneNumber"
                                                type="text"></input>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Company Email:</label>
                                            <input id="email" class="form-control" name="email" type="text"></input>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 text-center">
                                                <input type="submit" value="Register Company" class="btn btn-success"
                                                    style="background-color: green;">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include '../../includes/footer.php'; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>