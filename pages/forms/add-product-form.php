<?php
include '../../includes/header.php';
include '../../includes/session.php';
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
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>General Form</h1>
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
                            <div class="card card-success">
                                <div class="card-header">
                                    <h5 class="card-title">Add Product</h5>
                                </div>
                                <form action="ctrl-forms/add-product.php" method="POST">
                                    <div class="card-body">
                                        <label for="code">Product Code</label>
                                        <input class="form-control" type="text" id="code" name="code"
                                            placeholder="Enter Product Code" required>
                                        <br>
                                        <label for="brand">Product Brand</label>
                                        <input class="form-control" type="text" id="brand" name="brand"
                                            placeholder="Enter Product Brand" required>
                                        <br>
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            placeholder="Enter Description" required></textarea>
                                        <br>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>
    </div><!-- /.wrapper -->
    <?php include '../../includes/script.php'; ?>
</body>

</html>