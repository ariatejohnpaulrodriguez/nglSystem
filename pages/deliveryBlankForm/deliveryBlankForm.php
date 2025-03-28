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
                            <h1>Delivery Form Page</h1>
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
                                        <i class="fas fa-file-invoice"></i> Blank Form
                                    </h5>
                                </div>

                                <?php

                                include '../../includes/conn.php';

                                // Fetch company data
                                $sql = "SELECT company_id, name, address, phone_number, email, plant, plant_name, attention, image FROM companies";
                                $result = $conn->query($sql);

                                $companies = [];
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $companies[] = $row;
                                    }
                                }

                                $conn->close();
                                ?>

                                <form id="consumable-form" action="generate-pdf.php" method="POST" target="_blank">
                                    <div class="card-body">

                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-gray">
                                                        <div class="card-header">
                                                            <h3 class="card-title">From Company</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <select id="c-company-from" name="company_from_id"
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
                                                            <address id="c-company-from-details">
                                                                <!-- Company details will be populated here based on selection -->
                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="card card-gray">
                                                        <div class="card-header">
                                                            <h3 class="card-title">To Company</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <select id="c-company-to" name="company_to_id"
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
                                                            <address id="c-company-to-details">
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
                                                    <div class="card card-gray">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Fill Up Information</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Left Column -->
                                                                <div class="col-md-6">
                                                                    <label for="postingDate">Posting Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="c-datepicker"
                                                                            class="form-control form-control-sm"
                                                                            name="posting_date" readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"
                                                                                id="c-calendar-icon"
                                                                                style="cursor:pointer;">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <label for="plant">Plant:</label>
                                                                    <input type="text" id="c-plant" name="plant"
                                                                        class="form-control form-control-sm">

                                                                    <label for="poNumber">PO #:</label>
                                                                    <input type="text" id="c-poNumber" name="po_number"
                                                                        class="form-control form-control-sm">

                                                                    <label for="reference-po">Reference PO:</label>
                                                                    <input type="text" id="c-reference-po"
                                                                        name="reference_po"
                                                                        class="form-control form-control-sm">
                                                                </div>

                                                                <!-- Right Column -->
                                                                <div class="col-md-6">
                                                                    <label for="deliveryDate">Delivery Date</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="c-datepicker2"
                                                                            name="delivery_date"
                                                                            class="form-control form-control-sm"
                                                                            readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"
                                                                                id="c-calendar-icon2"
                                                                                style="cursor:pointer;">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <label for="c-drNumber">DR #:</label>
                                                                    <input type="text" id="c-drNumber" name="dr_number"
                                                                        class="form-control form-control-sm">

                                                                    <!-- Plant Name field -->
                                                                    <label for="plantName">Plant Name:</label>
                                                                    <input type="text" id="c-plantName" name="plantName"
                                                                        class="form-control form-control-sm">

                                                                    <label for="attention">Attention:</label>
                                                                    <input type="text" id="attention" name="attention"
                                                                        class="form-control form-control-sm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div class="row">
                                                <!-- New Product Table Card -->
                                                <div class="col-md-12 mb-4">
                                                    <div class="card card-gray">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Product Details</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="product-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60px;">Quantity</th>
                                                                            <th style="width: 80px;">Unit</th>
                                                                            <th style="width: 400px;">Description</th>
                                                                            <!-- Set a long width -->
                                                                            <th style="width: 120px;">Remarks</th>
                                                                            <th style="width: 80px;"
                                                                                class="text-center">Action</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="d-flex justify-content-center">
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    id="add-product-row">Add Product</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="card-footer d-flex justify-content-center"
                                            style="background: transparent;">
                                            <button type="submit" class="btn btn-dark">Export To PDF</button>
                                        </div>
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
</body>

</html>

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="../../plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../../plugins/raphael/raphael.min.js"></script>
<script src="../../plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../../plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="../../plugins/chart.js/Chart.min.js"></script>
<!-- DataTables & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Sparkline -->
<script src="../../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../plugins/moment/moment.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>

<script src="../../plugins/jsgrid/jsgrid.min.js"></script>
<script src="../../plugins/toastr/toastr.min.js"></script>

<script>
    $(document).ready(function () {
        function updateCompanyDetails(selectElement, detailsElement) {
            if (!selectElement) {
                console.error("selectElement is undefined in updateCompanyDetails");
                return;
            }

            try {
                var selectedOption = selectElement.options[selectElement.selectedIndex];
                var address = selectedOption.getAttribute("data-address");
                var attention = selectedOption.getAttribute("data-attention"); // Get Attention value
                var phone = selectedOption.getAttribute("data-phone");

                detailsElement.innerHTML = `
                <strong><i class="fas fa-building"></i> ${selectedOption.textContent}</strong><br>
                <i class="fas fa-map-marker-alt"></i> ${address}<br>
                <i class="fas fa-user"></i> ${attention}<br>
                <i class="fas fa-phone"></i> ${phone}
            `;

                // Set attention field. Important addition.
                $('#attention').val(attention);


            } catch (e) {
                console.error("Error in updateCompanyDetails:", e);
                detailsElement.innerHTML = "<p>Error loading company details.</p>";
            }
        }

        var companyFrom = $("#c-company-from");
        var companyFromDetails = $("#c-company-from-details");
        var companyTo = $("#c-company-to");
        var companyToDetails = $("#c-company-to-details");

        if (companyFrom.length && companyFromDetails.length) {
            companyFrom.on("click change", function () {
                updateCompanyDetails(companyFrom[0], companyFromDetails[0]);
            });
            updateCompanyDetails(companyFrom[0], companyFromDetails[0]);
        }

        if (companyTo.length && companyToDetails.length) {
            companyTo.on("click change", function () {
                updateCompanyDetails(companyTo[0], companyToDetails[0]);
            });
            updateCompanyDetails(companyTo[0], companyToDetails[0]);
        }

        $("#c-datepicker, #c-datepicker2").datepicker({
            showAnim: "fadeIn",
            dateFormat: "yy-mm-dd"
        });

        $("#c-calendar-icon").click(function () {
            $("#c-datepicker").datepicker("show");
        });

        $("#c-calendar-icon2").click(function () {
            $("#c-datepicker2").datepicker("show");
        });

        $("#c-company-to").change(function () {
            var companyID = $(this).val();

            $("#c-plant").val('');
            $("#c-plantName").val('');
            $("#attention").val(''); // Clear the attention field

            if (companyID) {
                var selectedCompany = $("#c-company-to option[value='" + companyID + "']");

                var plant = selectedCompany.data('plant');
                var plantName = selectedCompany.data('plant-name');
                var attention = selectedCompany.data('attention'); // Get attention from data attribute

                $("#c-plant").val(plant);
                $("#c-plantName").val(plantName);
                $("#attention").val(attention); // Set the attention field
            } else {
                $("#c-plant").val('');
                $("#c-plantName").val('');
                $("#attention").val(''); // Clear the attention field
            }
        });

        // Product Table Logic
        $("#add-product-row").click(function () {
            var newRow = `
                <tr>
                    <td><input type="text" class="form-control form-control-sm" name="quantity[]"></td>
                    <td><input type="text" class="form-control form-control-sm" name="unit[]"></td>
                    <td><input type="text" class="form-control form-control-sm" name="description[]"></td>
                    <td><input type="text" class="form-control form-control-sm" name="remarks[]" value=""></td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-danger btn-sm remove-product-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $("#product-table tbody").append(newRow);
        });

        // Remove Product Row
        $(document).on('click', '.remove-product-row', function () {
            $(this).closest('tr').remove();
        });
    });
</script>