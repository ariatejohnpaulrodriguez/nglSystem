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
                            <h1>Initial Inventory</h1>
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
                            <div class="card card-secondary">
                                <?php
                                include '../../includes/conn.php';

                                // Initialize variables
                                $employee_id = '';
                                $fullName = '';
                                $begBalance = '';
                                $endBalance = '';
                                $quantity = '';
                                $productType = '';
                                $checkedBy = '';
                                $approvedBy = '';
                                $date_id = '';
                                $note = '';

                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $employee_id = $_POST['employee_id'];

                                    // Fetch the employee data based on the ID
                                    $query = "SELECT CONCAT(e.first_name, ' ', e.last_name) AS fullName,
                                    i.beg_balance, i.end_balance, i.quantity, i.product_type, i.checked_by, i.approved_by, i.date_id
                                    FROM employees e
                                    LEFT JOIN inventories i ON e.employee_id = i.checked_by OR e.employee_id = i.approved_by
                                    LEFT JOIN dates d ON i.date_id = d.date_id
                                    WHERE e.employee_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $employee_id);
                                    $stmt->execute();
                                    $stmt->bind_result($fullName, $begBalance, $endBalance, $quantity, $productType, $checkedBy, $approvedBy, $date_id);
                                    $stmt->fetch();
                                    $stmt->close();
                                }
                                ?>


                                <div class="card-header">
                                    <h5 class="card-title">Fill up</h5>
                                </div>
                                <form action="ctrl-inventory/inventory-form-submit.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="product_id">Select Registered Code</label>
                                                    <select class="form-control" id="product_id" name="product_id"
                                                        required>
                                                        <option value="" disabled>-- Select --</option>
                                                        <?php
                                                        include '../../includes/conn.php';
                                                        // Fetch products from the database
                                                        $productsQuery = "SELECT product_id, code, brand, description FROM products";
                                                        $productsResult = $conn->query($productsQuery);
                                                        if ($productsResult->num_rows > 0) {
                                                            while ($row = $productsResult->fetch_assoc()) {
                                                                $selected = ($row['product_id'] == $currentProduct) ? 'selected' : '';
                                                                echo "<option value='" . $row['product_id'] . "' $selected>" . $row['code'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>


                                                <div class="form-group">
                                                    <label for="begBalance">Beginning Balance</label>
                                                    <input class="form-control" type="text" id="begBalance"
                                                        name="begBalance"
                                                        value="<?php echo htmlspecialchars($begBalance); ?>"
                                                        placeholder="Enter Beginning Balance" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="endBalance">End Balance</label>
                                                    <input class="form-control" type="text" id="endBalance"
                                                        name="endBalance"
                                                        value="<?php echo htmlspecialchars($endBalance); ?>"
                                                        placeholder="Enter End Balance" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <div class="input-group">
                                                        <input type="text" id="datepicker3"
                                                            class="form-control form-control-sm" name="date" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="calendar-icon"
                                                                style="cursor:pointer;">
                                                                <i class="fas fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="note">Note</label>
                                                    <input class="form-control" type="text" id="note" name="note"
                                                        value="<?php echo htmlspecialchars($note); ?>"
                                                        placeholder="Enter Quantity" required>
                                                </div>

                                            </div>

                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status_id" required>
                                                        <option value="" disabled>-- Select --</option>
                                                        <?php
                                                        include '../../includes/conn.php';
                                                        // Fetch statuses from the database
                                                        $statusesQuery = "SELECT status_id, status_name FROM statuses";
                                                        $statusesResult = $conn->query($statusesQuery);
                                                        if ($statusesResult->num_rows > 0) {
                                                            while ($row = $statusesResult->fetch_assoc()) {
                                                                $selected = ($row['status_id'] == $currentStatus) ? 'selected' : '';
                                                                echo "<option value='" . $row['status_id'] . "' $selected>" . $row['status_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="quantity">Quantity</label>
                                                    <input class="form-control" type="text" id="quantity"
                                                        name="quantity"
                                                        value="<?php echo htmlspecialchars($quantity); ?>"
                                                        placeholder="Enter Quantity" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="productType">Product Type</label>
                                                    <input class="form-control" type="text" id="productType"
                                                        name="productType"
                                                        value="<?php echo htmlspecialchars($productType); ?>"
                                                        placeholder="Enter Product Type">
                                                </div>

                                                <div class="form-group">
                                                    <label for="checkedBy">Checked By</label>
                                                    <select class="form-control" id="checkedBy" name="checkedBy"
                                                        required>
                                                        <option value="" disabled>-- Select --</option>
                                                        <?php
                                                        // Fetch employees from the database
                                                        $employeesQuery = "SELECT employee_id, CONCAT(first_name, ' ', last_name) AS fullName FROM employees";
                                                        $employeesResult = $conn->query($employeesQuery);
                                                        if ($employeesResult->num_rows > 0) {
                                                            while ($row = $employeesResult->fetch_assoc()) {
                                                                $selected = ($row['employee_id'] == $checkedBy) ? 'selected' : '';
                                                                echo "<option value='" . $row['employee_id'] . "' $selected>" . $row['fullName'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="approvedBy">Approved By</label>
                                                    <select class="form-control" id="approvedBy" name="approvedBy"
                                                        required>
                                                        <option value="" disabled>-- Select --</option>
                                                        <?php
                                                        // Fetch employees from the database
                                                        $employeesQuery = "SELECT employee_id, CONCAT(first_name, ' ', last_name) AS fullName FROM employees";
                                                        $employeesResult = $conn->query($employeesQuery);
                                                        if ($employeesResult->num_rows > 0) {
                                                            while ($row = $employeesResult->fetch_assoc()) {
                                                                $selected = ($row['employee_id'] == $approvedBy) ? 'selected' : '';
                                                                echo "<option value='" . $row['employee_id'] . "' $selected>" . $row['fullName'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="card-footer text-center">
                                            <button type="submit" class="btn btn-primary">Confirm</button>
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