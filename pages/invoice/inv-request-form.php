<?php
include '../../includes/header.php';
include '../../includes/session.php';
include '../../includes/check-permission.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
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
                            <h1>ReStock Page</h1>
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
                                <div class="card-header" style="display: flex; align-items: center;">
                                    <h5 class="card-title" style="margin-right: auto;">
                                        <i class="fas fa-file-invoice"></i> Request ReStock Delivery Form
                                    </h5>
                                    <a href="../../pages/transaction/received-deliveries.php"
                                        class="btn btn-light btn-xs" style="color: black;">ReStock List</a>
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

                                $conn->close();
                                ?>

                                <form id="invoice-form" action="javascript:void(0)">
                                    <div class="card-body">

                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-info">
                                                        <div class="card-header">
                                                            <h3 class="card-title">From Company</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <select id="i-company-from" name="company_from_id"
                                                                class="form-control">
                                                                <?php foreach ($companies as $company): ?>
                                                                    <option value="<?php echo $company['company_id']; ?>"
                                                                        data-address="<?php echo $company['address']; ?>"
                                                                        data-attention="<?php echo $company['attention']; ?>"
                                                                        data-phone=" <?php echo $company['phone_number']; ?>">
                                                                        <?php echo $company['name']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select><br>
                                                            <address id="i-company-from-details">
                                                                <!-- Company details will be populated here based on selection -->
                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-info">
                                                        <div class="card-header">
                                                            <h3 class="card-title">To Company</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <select id="i-company-to" name="company_to_id"
                                                                class="form-control">
                                                                <?php foreach ($companies as $company): ?>
                                                                    <option value="<?php echo $company['company_id']; ?>"
                                                                        data-plant="<?php echo $company['plant']; ?>"
                                                                        data-plant-name="<?php echo $company['plant_name']; ?>"
                                                                        data-address="<?php echo $company['address']; ?>"
                                                                        data-attention="<?php echo $company['attention']; ?>"
                                                                        data-phone="<?php echo $company['phone_number']; ?>">
                                                                        <?php echo $company['name']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select><br>
                                                            <address id="i-company-to-details">
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
                                                    <div class="card card-info">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Fill Up Information</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Left Column -->
                                                                <div class="col-md-6">
                                                                    <label for="postingDate">Posting Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="i-datepicker"
                                                                            class="form-control form-control-sm"
                                                                            name="postingDate" readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"
                                                                                id="i-calendar-icon"
                                                                                style="cursor:pointer;">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <label for="plant">Plant:</label>
                                                                    <input type="text" id="i-plant" name="plant"
                                                                        class="form-control form-control-sm" readonly>

                                                                    <label for="poNumber">PO #:</label>
                                                                    <input type="text" id="i-poNumber"
                                                                        class="form-control form-control-sm">

                                                                    <label for="reference-po">Reference PO:</label>
                                                                    <input type="text" id="i-reference-po"
                                                                        class="form-control form-control-sm">
                                                                </div>

                                                                <!-- Right Column -->
                                                                <div class="col-md-6">
                                                                    <label for="deliveryDate">Delivery Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="i-datepicker2"
                                                                            name="deliveryDate"
                                                                            class="form-control form-control-sm"
                                                                            readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"
                                                                                id="i-calendar-icon2"
                                                                                style="cursor:pointer;">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <label for="i-drNumber">DR #:</label>
                                                                    <input type="text" id="i-drNumber"
                                                                        class="form-control form-control-sm">

                                                                    <!-- Plant Name field -->
                                                                    <label for="plantName">Plant Name:</label>
                                                                    <input type="text" id="i-plantName" name="plantName"
                                                                        class="form-control form-control-sm" readonly>
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
                                                            <th>Unit</th> <!-- Added Unit column -->
                                                            <th>Code</th>
                                                            <th>Brand</th>
                                                            <th>Description</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="i-product-table-body"></tbody>
                                                    <!-- Table rows will be dynamically added here -->
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="6" class="text-center">
                                                                <button type="button" id="i-add-products"
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
                                            <button type="submit" class="btn btn-primary">Request Incoming
                                                Delivery</button>
                                        </div>
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
    </div>
</body>

</html>