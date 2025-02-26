<?php
include '../../includes/conn.php';
include '../../includes/session.php';
include '../../includes/header.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include '../../includes/navbar.php' ?>

        <?php include '../../includes/sidebar.php' ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Inventory Page</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container">
                    <div class="col-md-13">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h3 class="card-title"><i class="fas fa-info-circle"></i> <strong>Note:</strong> Select
                                    the row for additional information</h3>
                            </div>

                            <?php
                            include '../../includes/conn.php'; // Database connection
                            
                            // Fetch inventory data from the database
                            $query = "SELECT i.invoice_product_id, 
                 ip.product_id, 
                 p.description, 
                 p.brand, 
                 p.code, 
                 d.date_value, 
                 i.beg_balance, 
                 i.end_balance, 
                 i.quantity, 
                 i.product_type, 
                 CONCAT(e1.first_name, ' ', e1.last_name) AS checked_by, 
                 CONCAT(e2.first_name, ' ', e2.last_name) AS approved_by, 
                 i.notes
          FROM inventories i
          JOIN invoice_products ip ON i.invoice_product_id = ip.invoice_product_id
          JOIN products p ON ip.product_id = p.product_id
          JOIN dates d ON i.date_id = d.date_id
          LEFT JOIN employees e1 ON i.checked_by = e1.employee_id
          LEFT JOIN employees e2 ON i.approved_by = e2.employee_id";

                            $result = $conn->query($query);

                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }
                            ?>

                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product ID</th>
                                                <th>Description</th>
                                                <th>Brand</th>
                                                <th>Code</th>
                                                <th>Date</th>
                                                <th>Availability</th>
                                                <th>Quantity</th>
                                                <th>Checked By</th>
                                                <th>Approved By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {

                                                    // Ensure quantity exists in the row
                                                    $quantity = isset($row['quantity']) ? $row['quantity'] : 0;

                                                    // Determine progress bar and badge color based on quantity
                                                    if ($quantity <= 25) {
                                                        $colorClass = 'bg-danger'; // Red
                                                    } elseif ($quantity <= 75) {
                                                        $colorClass = 'bg-warning'; // Orange
                                                    } else {
                                                        $colorClass = 'bg-success'; // Green
                                                    }

                                                    // Calculate percentage for progress bar (ensuring it doesn't exceed 100%)
                                                    $progressPercentage = min($quantity, 100);

                                                    echo "<tr class='clickable-row' data-toggle='collapse' data-target='#details" . $row['product_id'] . "'>";
                                                    echo "<td>" . $row['product_id'] . "</td>";
                                                    echo "<td>" . $row['description'] . "</td>";
                                                    echo "<td>" . $row['brand'] . "</td>";
                                                    echo "<td>" . $row['code'] . "</td>";
                                                    echo "<td>" . $row['date_value'] . "</td>";

                                                    // Progress Bar (AdminLTE 3.2.0 supports bg-danger, bg-warning, bg-success)
                                                    echo "<td>";
                                                    echo "<div class='progress progress-xs' style='height: 10px; margin-bottom: 0;'>";
                                                    echo "<div class='progress-bar " . $colorClass . "' style='width: " . $progressPercentage . "%;'></div>";
                                                    echo "</div>";
                                                    echo "</td>";

                                                    // Quantity Badge with the same background color
                                                    echo "<td><span class='badge " . $colorClass . "'>" . $quantity . "</span></td>";

                                                    echo "<td>" . ($row['checked_by'] ?? 'N/A') . "</td>";
                                                    echo "<td>" . ($row['approved_by'] ?? 'N/A') . "</td>";
                                                    echo "</tr>";

                                                    // Hidden Row for Product Details
                                                    echo "<tr id='details" . $row['product_id'] . "' class='collapse'>";
                                                    echo "<td colspan='12'>";
                                                    echo "<div class='card wrapper'>";
                                                    echo "<div class='card-header bg-info d-flex align-items-center justify-content-between'>";
                                                    echo "<h3 class='card-title text-white'>Product Details</h3>";
                                                    echo "<button class='btn btn-warning btn-sm ml-auto'><i class='fas fa-calendar-alt'></i> Select Date</button>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "<div class='row'>";
                                                    echo "<div class='col-12 col-sm-4'><div class='info-box bg-light'><div class='info-box-content'><span class='info-box-text text-center text-muted'>Beginning Balance</span><span class='info-box-number text-center text-muted mb-0'>" . $row['beg_balance'] . "</span></div></div></div>";
                                                    echo "<div class='col-12 col-sm-4'><div class='info-box bg-light'><div class='info-box-content'><span class='info-box-text text-center text-muted'>End Balance</span><span class='info-box-number text-center text-muted mb-0'>" . $row['end_balance'] . "</span></div></div></div>";
                                                    echo "<div class='col-12 col-sm-4'><div class='info-box bg-light'><div class='info-box-content'><span class='info-box-text text-center text-muted'>Quantity Counted</span><span class='info-box-number text-center text-muted mb-0'>" . $row['quantity'] . "</span></div></div></div>";
                                                    echo "<div class='col-12 col-sm-4'><div class='info-box bg-light'><div class='info-box-content'><span class='info-box-text text-center text-muted'>Discrepancy</span><span class='info-box-number text-center text-muted mb-0'>Unavailable as of the moment</span></div></div></div>";
                                                    echo "<div class='col-12 col-sm-4'><div class='info-box bg-light'><div class='info-box-content'><span class='info-box-text text-center text-muted'>Note</span><span class='info-box-number text-center text-muted mb-0'>" . $row['notes'] . "</span></div></div></div>";
                                                    echo "<div class='col-12 col-sm-4'><div class='info-box bg-light'><div class='info-box-content'><span class='info-box-text text-center text-muted'>Product Type</span><span class='info-box-number text-center text-muted mb-0'>" . ($row['product_type'] ?? 'N/A') . "</span></div></div></div>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='12'>No records found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <!-- /.card -->

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php' ?>
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php' ?>
</body>

</html>