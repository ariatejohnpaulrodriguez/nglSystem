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
                            <h1>Update Form</h1>
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
                                    <h5 class="card-title">Update Product</h5>
                                </div>
                                <form action="ctrl-data/update-ctrl-product.php" method="POST">
                                    <div class="card-body">
                                        <?php
                                        include '../../includes/conn.php';

                                        if (isset($_POST['product_id'])) {
                                            $product_id = $_POST['product_id']; // Retrieve product_id from form submission
                                        } else {
                                            die("Product ID is missing.");
                                        }

                                        $query = "SELECT * FROM products WHERE product_id = ?";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("i", $product_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $product = $result->fetch_assoc();
                                        ?>

                                        <label for="product_id">Product ID</label>
                                        <input class="form-control" type="text" id="product_id" name="product_id"
                                            value="<?php echo $product['product_id']; ?>" required readonly>
                                        <br>
                                        <label for="code">Product Code</label>
                                        <input class="form-control" type="text" id="code" name="product_code"
                                            value="<?php echo $product['code']; ?>" required>
                                        <br>
                                        <label for="brand">Product Brand</label>
                                        <input class="form-control" type="text" id="brand" name="product_brand"
                                            value="<?php echo $product['brand']; ?>" required>
                                        <br>
                                        <label for="description">Description</label>
                                        <input class="form-control" type="text" id="description"
                                            name="product_description"
                                            value="<?php echo $product['description']; ?>" required>
                                        <br>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </section>
        </div>
        <?php include '../../includes/footer.php'; ?>
        <!-- /.content-wrapper -->
    </div><!-- /.wrapper -->

  <?php include '../../includes/script.php'; ?>
</body>
</html>