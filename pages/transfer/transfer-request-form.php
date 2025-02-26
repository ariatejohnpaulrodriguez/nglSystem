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
                            <h1>Transfer Page</h1>
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
                        <div class="col-md-12">

                            <!-- Form Element sizes -->
                            <div class="card card-gray">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="fas fa-file-invoice"></i> Request Transfer Delivery
                                        Form</h5>
                                </div>

                                <?php

                                include '../../includes/conn.php';

                                // Fetch company data
                                $sql = "SELECT company_id, name, address, phone_number, email, plant, plant_name, attention FROM companies";
                                $result = $conn->query($sql);

                                $companies = [];
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $companies[] = $row;
                                    }
                                }

                                $sql = "SELECT status_id, status_name FROM statuses";
                                $result = $conn->query($sql);

                                $statuses = [];
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $statuses[] = $row;
                                    }
                                }

                                $conn->close();
                                ?>

                                <form id="transfer-form" action="javascript:void(0)">
                                    <div class="card-body">

                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-purple">
                                                        <div class="card-header">
                                                            <h3 class="card-title">From Company</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <select id="company-from" name="company_from_id"
                                                                class="form-control">
                                                                <?php foreach ($companies as $company): ?>
                                                                    <option value="<?php echo $company['company_id']; ?>"
                                                                        data-plant="<?php echo $company['plant']; ?>"
                                                                        data-plant-name="<?php echo $company['plant_name']; ?>"
                                                                        data-address="<?php echo $company['address']; ?>"
                                                                        data-attention="<?php echo $company['attention']; ?>"
                                                                        data-phone=" <?php echo $company['phone_number']; ?>">
                                                                        <?php echo $company['name']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select><br>
                                                            <address id="company-from-details">
                                                                <!-- Company details will be populated here based on selection -->
                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-purple">
                                                        <div class="card-header">
                                                            <h3 class="card-title">To Company</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <select id="company-to" name="company_to_id"
                                                                class="form-control">
                                                                <?php foreach ($companies as $company): ?>
                                                                    <option value="<?php echo $company['company_id']; ?>"
                                                                        data-address="<?php echo $company['address']; ?>"
                                                                        data-attention="<?php echo $company['attention']; ?>"
                                                                        data-phone="<?php echo $company['phone_number']; ?>">
                                                                        <?php echo $company['name']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select><br>
                                                            <address id="company-to-details">
                                                                <!-- Company details will be populated here based on selection -->
                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div class="row">
                                                <!-- Your existing content for Date and Employee Information -->
                                                <div class="col-md-12 mb-4">
                                                    <div class="card card-purple">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Fill Up Information</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Left Column -->
                                                                <div class="col-md-6">
                                                                    <label for="postingDate">Posting Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="datepicker3"
                                                                            class="form-control form-control-sm"
                                                                            name="postingDate" readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"
                                                                                id="calendar-icon"
                                                                                style="cursor:pointer;">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <label for="plant">Plant:</label>
                                                                    <input type="text" id="plant" name="plant"
                                                                        class="form-control form-control-sm" readonly>

                                                                    <label for="poNumber">PO #:</label>
                                                                    <input type="text" id="poNumber"
                                                                        class="form-control form-control-sm">

                                                                    <label for="reference-po">Reference PO:</label>
                                                                    <input type="text" id="reference-po"
                                                                        class="form-control form-control-sm">
                                                                </div>

                                                                <!-- Right Column -->
                                                                <div class="col-md-6">
                                                                    <label for="deliveryDate">Delivery Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="datepicker4"
                                                                            name="deliveryDate"
                                                                            class="form-control form-control-sm"
                                                                            readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"
                                                                                id="calendar-icon2"
                                                                                style="cursor:pointer;">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <label for="drNumber">DR #:</label>
                                                                    <input type="text" id="drNumber"
                                                                        class="form-control form-control-sm">

                                                                    <!-- Plant Name field -->
                                                                    <label for="plantName">Plant Name:</label>
                                                                    <input type="text" id="plantName" name="plantName"
                                                                        class="form-control form-control-sm" readonly>

                                                                    <!-- Status field -->
                                                                    <label for="status">Status:</label>
                                                                    <select id="status" name="status"
                                                                        class="form-control form-control-sm">
                                                                        <?php foreach ($statuses as $status): ?>
                                                                            <option
                                                                                value="<?php echo htmlspecialchars($status['status_id']); ?>">
                                                                                <?php echo htmlspecialchars($status['status_name']); ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>



                                        <!-- Table row -->
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Qty</th>
                                                            <th>Stock</th>
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
                                                            <td colspan="6" class="text-center">
                                                                <button type="button" id="add-t-products"
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

                                        <div class="card-footer d-flex justify-content-center"
                                            style="background: transparent;">
                                            <button type="submit" class="btn btn-primary">Request Transfer
                                                Delivery</button>
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

        <?php include '../../includes/script.php'; ?>
        <script src="../../dist/js/custom-transfer.js"></script>
    </div>
</body>

</html>