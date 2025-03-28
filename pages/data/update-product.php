<?php
include '../../includes/header.php';
include '../../includes/session.php';
include '../../includes/check-permission.php';
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
                                <?php
                                include '../../includes/conn.php';

                                if (isset($_POST['product_id'])) {
                                    $product_id = $_POST['product_id'];
                                } else {
                                    die("Product ID is missing.");
                                }

                                // Fetch product details including unit_id
                                $query = "SELECT p.*, u.unit_name 
                                FROM products p
                                LEFT JOIN units u ON p.unit_id = u.unit_id
                                WHERE p.product_id = ?";

                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("i", $product_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $product = $result->fetch_assoc();
                                $selected_unit_id = $product['unit_id'] ?? '';

                                // Fetch available units for dropdown
                                $unitQuery = "SELECT * FROM units ORDER BY unit_name ASC";
                                $unitResult = $conn->query($unitQuery);
                                ?>

                                <form action="ctrl-data/update-ctrl-product.php" method="POST">
                                    <div class="card-body">
                                        <label for="product_id">Product ID</label>
                                        <input class="form-control" type="text" id="product_id" name="product_id"
                                            value="<?php echo htmlspecialchars($product['product_id']); ?>" required
                                            readonly>
                                        <br>

                                        <label for="code">Product Code</label>
                                        <input class="form-control" type="text" id="code" name="code"
                                            value="<?php echo htmlspecialchars($product['code']); ?>" required>
                                        <br>

                                        <label for="brand">Product Brand</label>
                                        <input class="form-control" type="text" id="brand" name="brand"
                                            value="<?php echo htmlspecialchars($product['brand']); ?>" required>
                                        <br>

                                        <label for="description">Description</label>
                                        <input class="form-control" type="text" id="description" name="description"
                                            value="<?php echo htmlspecialchars($product['description']); ?>" required>
                                        <br>

                                        <label for="unit_id">Unit</label>
                                        <select class="form-control" id="unit_id" name="unit_id" required>
                                            <option value="">-- Select Unit --</option>
                                            <?php while ($unit = $unitResult->fetch_assoc()): ?>
                                                <option value="<?php echo $unit['unit_id']; ?>" 
                                                    <?php echo ($unit['unit_id'] == $selected_unit_id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($unit['unit_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
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