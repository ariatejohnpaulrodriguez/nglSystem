<?php
include '../../includes/header.php';
include '../../includes/session.php';
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
                            <h1>Invoice Page</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            <?php include '../../includes/conn.php';

                            // Fetch company data
                            $sql = "SELECT company_id, name, address, phone_number, email FROM companies";
                            $result = $conn->query($sql);

                            $companies = [];
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $companies[] = $row;
                                }
                            }

                            $conn->close();
                            ?>
                            <form id="invoice-form" method="post">
                                <!-- Main content -->
                                <div class="invoice p-3 mb-3">
                                    <!-- title row -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>
                                                <i class="fas fa-file-invoice"></i> Invoice Information
                                                <small class="float-right">
                                                    Date:
                                                    <input type="text" id="datepicker"
                                                        class="form-control form-control-sm"
                                                        style="display:inline-block; width:auto;" readonly>
                                                    <i class="fas fa-calendar-alt" id="calendar-icon"
                                                        style="cursor:pointer;"></i>
                                                </small>
                                            </h4>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- info row -->
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                            <label for="company-from">From</label>
                                            <select id="company-from" name="company_from_id">
                                                <?php foreach ($companies as $company): ?>
                                                    <option value="<?php echo $company['company_id']; ?>"
                                                        data-address="<?php echo $company['address']; ?>"
                                                        data-phone="<?php echo $company['phone_number']; ?>"
                                                        data-email="<?php echo $company['email']; ?>">
                                                        <?php echo $company['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <address id="company-from-details">
                                                <!-- Company details will be populated here based on selection -->
                                            </address>
                                        </div>

                                        <div class="col-sm-4 invoice-col">
                                            <label for="company-to">To</label>
                                            <select id="company-to" name="company_to_id">
                                                <?php foreach ($companies as $company): ?>
                                                    <option value="<?php echo $company['company_id']; ?>"
                                                        data-address="<?php echo $company['address']; ?>"
                                                        data-phone="<?php echo $company['phone_number']; ?>"
                                                        data-email="<?php echo $company['email']; ?>">
                                                        <?php echo $company['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <address id="company-to-details">
                                                <!-- Company details will be populated here based on selection -->
                                            </address>
                                        </div>

                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col text-right">
                                            <b>Invoice #007612</b><br>
                                            <br>
                                            <b>Order ID:</b> 4F3S8J<br>
                                            <b>Empoyee ID:</b> <?php echo $_SESSION['role_id']; ?>
                                            <br>
                                            <b>Employee:</b> <?php echo $_SESSION['role']; ?>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->

                                    <!-- Table row -->
                                    <div class="row">
                                        <div class="col-12 table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Qty</th>
                                                        <th>Code</th>
                                                        <th>Brand</th>
                                                        <th>Description</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-table-body"></tbody>
                                                <!-- Table rows will be dynamically added here -->
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <button type="button" id="add-product"
                                                                class="btn btn-primary btn-sm">
                                                                <i class="fas fa-plus"></i> Add Product
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.col -->

                                    <div class="row no-print">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success float-right">
                                                <i class="fas fa-file-invoice"></i> Submit Invoice
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- /.invoice -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <?php include '../../includes/footer.php'; ?>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>