<?php
include '../../includes/conn.php';
include '../../includes/session.php';

// Check user's role
$userRole = $_SESSION['role'];
?>

<?php
include '../../includes/header.php';
?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include '../../includes/navbar.php'; ?>
        <!-- /.navbar -->
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Invoice List</h3>
                                    <a href="export-csv-product.php" class="btn btn-xs btn-success"
                                        style="margin-left: 20px;">Export as
                                        CSV</a><a href="export-pdf-product.php" class="btn btn-xs btn-danger"
                                        style="margin-left: 10px;">Export as
                                        PDF</a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>From Company</th>
                                                <th>To Company</th>
                                                <th>Delivery Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL query to fetch invoice data, company details, and date details
                                            $sql = "SELECT 
i.invoice_id, 
i.from_company_id, 
i.to_company_id, 
i.dr_id, 
i.po_id, 
i.reference_po_id,
fc.name AS from_company_name, 
fc.address AS from_company_address, 
fc.phone_number AS from_company_phone,
fc.email AS from_company_email,
fc.plant AS from_company_plant,
fc.plant_name AS from_company_plant_name,
fc.attention AS from_company_attention,
tc.name AS to_company_name, 
tc.address AS to_company_address, 
tc.phone_number AS to_company_phone,
tc.email AS to_company_email,
tc.plant AS to_company_plant,
tc.plant_name AS to_company_plant_name,
tc.attention AS to_company_attention,
pd.date_id AS posting_date_id, 
pd.date_value AS posting_date_value,
dd.date_id AS delivery_date_id, 
dd.date_value AS delivery_date_value
FROM invoices i
LEFT JOIN companies fc ON i.from_company_id = fc.company_id
LEFT JOIN companies tc ON i.to_company_id = tc.company_id
LEFT JOIN dates pd ON i.posting_date = pd.date_id
LEFT JOIN dates dd ON i.delivery_date = dd.date_id";

                                            $result = mysqli_query($conn, $sql);


                                            $result = mysqli_query($conn, $sql);

                                            // Check if there are any rows returned
                                            if (mysqli_num_rows($result) > 0) {
                                                // Loop through the rows and display them in the table
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row["invoice_id"] . "</td>";
                                                    echo "<td>" . $row["from_company_name"] . "</td>";
                                                    echo "<td>" . $row["to_company_name"] . "</td>";
                                                    echo "<td>" . $row["delivery_date_value"] . "</td>";
                                                    echo "<td>";

                                                    // Show Edit and Delete buttons only if the user is not a Warehouse Man
                                                    if ($userRole != "Warehouse Man") {
                                                        echo "<form action='view-invoice.php' method='post' style='display:inline-block;'>";
                                                        echo "<input type='hidden' name='invoice_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='View' class='btn btn-primary'>";
                                                        echo "</form>";
                                                        echo "<form action='ctrl-data/delete-product.php' method='post' style='display:inline-block;' onsubmit='return confirmDelete()'>";
                                                        echo "<input type='hidden' name='invoice_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='Delete' class='btn btn-danger'>";
                                                        echo "</form>";

                                                        // Additional Action Buttons (without changing the form structure)
                                            
                                                        // Pending Button
                                                        echo "<form action='ctrl-data/pending-invoice.php' method='post' style='display:inline-block;'>";
                                                        echo "<input type='hidden' name='invoice_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='Pending' class='btn btn-info'>";
                                                        echo "</form>";

                                                        // Cancelled Button
                                                        echo "<form action='ctrl-data/cancel-invoice.php' method='post' style='display:inline-block;'>";
                                                        echo "<input type='hidden' name='invoice_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='Cancelled' class='btn btn-warning'>";
                                                        echo "</form>";

                                                        // Rejected Button
                                                        echo "<form action='ctrl-data/reject-invoice.php' method='post' style='display:inline-block;'>";
                                                        echo "<input type='hidden' name='invoice_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='Reject' class='btn btn-danger'>";
                                                        echo "</form>";

                                                        // Approve Button
                                                        echo "<form action='ctrl-data/approve-invoice.php' method='post' style='display:inline-block;'>";
                                                        echo "<input type='hidden' name='invoice_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='Approve' class='btn btn-success'>";
                                                        echo "</form>";

                                                        // Blocked Button
                                                        echo "<form action='ctrl-data/block-invoice.php' method='post' style='display:inline-block;'>";
                                                        echo "<input type='hidden' name='invoice_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='Blocked' class='btn btn-dark'>";
                                                        echo "</form>";
                                                    } else {
                                                        echo "<form action='view-product.php' method='post' style='display:inline-block;'>";
                                                        echo "<input type='hidden' name='product_id' value='" . $row["invoice_id"] . "'>";
                                                        echo "<input type='submit' value='View' class='btn btn-primary'>";
                                                        echo "</form>";
                                                    }

                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No data found</td></tr>";
                                            }

                                            mysqli_close($conn);
                                            ?>
                                        </tbody>
                                        <!-- JavaScript for Confirmation -->
                                        <script>
                                            function confirmDelete() {
                                                return confirm("Are you sure you want to delete this product?");
                                            }
                                        </script>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <?php include '../../includes/script.php'; ?>
</body>

</html>