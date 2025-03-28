<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>

<script src="../../plugins/custom/custom-invoice.js"></script>
<script src="../../plugins/custom/custom-transfer.js"></script>

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
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>

<script src="../../plugins/jsgrid/jsgrid.min.js"></script>
<script src="../../plugins/toastr/toastr.min.js"></script>


<script>
    function deleteRoleModal(role_id) {
        // Set the role_id in the hidden input field
        document.getElementById('role_id_to_delete').value = role_id;
        // Show the modal with the updated ID
        $('#roleDeleteModal').modal('show');
    }

    function deleteGenderModal(gender_id) {
        // Set the role_id in the hidden input field
        document.getElementById('gender_id_to_delete').value = gender_id;
        // Show the modal with the updated ID
        $('#genderDeleteModal').modal('show');
    }

    function deleteStatusModal(status_id) {
        // Set the role_id in the hidden input field
        document.getElementById('status_id_to_delete').value = status_id;
        // Show the modal with the updated ID
        $('#statusDeleteModal').modal('show');
    }

    function deleteEmployeeModal(employee_id) {
        // Set the role_id in the hidden input field
        document.getElementById('employee_id_to_delete').value = employee_id;
        // Show the modal with the updated ID
        $('#employeeDeleteModal').modal('show');
    }

    function deleteProductModal(product_id) {
        // Set the role_id in the hidden input field
        document.getElementById('product_id_to_delete').value = product_id;
        // Show the modal with the updated ID
        $('#productDeleteModal').modal('show');
    }

    function deleteCompanyModal(company_id) {
        // Set the role_id in the hidden input field
        document.getElementById('company_id_to_delete').value = company_id;
        // Show the modal with the updated ID
        $('#companyDeleteModal').modal('show');
    }

    function deletePermissionModal(role_id, permission_id) {
        // Set the role_id and permission_id in the hidden input fields
        document.getElementById('role_id_to_delete').value = role_id;
        document.getElementById('permission_id_to_delete').value = permission_id;
        // Show the modal
        $('#permissionDeleteModal').modal('show');
    }

    function deleteStatusPermissionModal(role_id, status_id) {
        document.getElementById("role_id_to_delete_status").value = role_id;
        document.getElementById("status_id_to_delete").value = status_id;
        $("#statusPermissionDeleteModal").modal("show");
    }

    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000", // Duration in milliseconds
        "extendedTimeOut": "1000"
    };

    // Handle Toastr notifications for success and error session messages
    <?php if (isset($_SESSION['success'])): ?>
        toastr.success("<?php echo $_SESSION['success']; ?>");
        <?php unset($_SESSION['success']); // Clear success message ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        toastr.error("<?php echo $_SESSION['error']; ?>");
        <?php unset($_SESSION['error']); // Clear error message ?>
    <?php endif; ?>
</script>

<script>
    $(document).on('click', '.invoice-pdf-btn', function () {
        let invoiceId = $(this).data('id');
        console.log("PDF button clicked for invoice ID: " + invoiceId);
        window.open('../../pages/invoice/invoice-print.php?invoice_id=' + invoiceId, '_blank');
    });
</script>

<script>
    $(document).on('click', '.transfer-pdf-btn', function () {
        let transferID = $(this).data('id');
        console.log("PDF button clicked for transfer ID: " + transferID);
        window.open('../../pages/transfer/transfer-print.php?transfer_id=' + transferID, '_blank');
    });
</script>

<script>
    function previewImage(event) {
        const output = document.getElementById('companyLogo');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function () {
            URL.revokeObjectURL(output.src) // Free memory
        }
    }
</script>