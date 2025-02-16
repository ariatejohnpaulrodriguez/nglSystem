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

<!-- AdminLTE for demo purposes -->
<!-- <script src="../../dist/js/demo.js"></script>-->

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

<!-- Page specific script -->
<script>
    $(function () {
        // Check if DataTable is already initialized for #example1
        if (!$.fn.dataTable.isDataTable('#example1')) {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }

        // Check if DataTable is already initialized for #example2
        if (!$.fn.dataTable.isDataTable('#example2')) {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        }
    });
</script>

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

<!-- jsGrid -->
<script src="../../plugins/jsgrid/demos/db.js"></script>
<script src="../../plugins/jsgrid/jsgrid.min.js"></script>

<!-- Page specific script -->
<script>
    $(function () {
        // Initialize datepickers
        $("#datepicker, #datepicker2").datepicker({
            showAnim: "fadeIn",
            dateFormat: "yy-mm-dd" // Format to match MySQL DATE type
        });

        // Show datepicker when the calendar icon is clicked
        $("#calendar-icon").click(function () {
            $("#datepicker").datepicker("show");
        });

        $("#calendar-icon2").click(function () {
            $("#datepicker2").datepicker("show");
        });

        // Add product row to the table
        document.getElementById('add-products').addEventListener('click', function () {
            var tbody = document.getElementById('product-table-body');
            var tr = document.createElement('tr');

            tr.innerHTML = `<td><input type="number" class="form-control form-control-sm" name="quantity[]" min="1" value="1"></td>
        <td>
        <select class="form-control form-control-sm" name="product_code[]">
            <option value="">Select Code</option>
            <?php
            include '../../includes/conn.php';
            $sql = "SELECT product_id, code, brand, description FROM products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()): ?>
                <option value="<?php echo $product['product_id']; ?>"
                data-brand="<?php echo $product['brand']; ?>"
                data-description="<?php echo $product['description']; ?>">
                <?php echo $product['code']; ?>
                </option>
            <?php endwhile;
            }
            $conn->close();
            ?>
        </select>
        </td>
        <td class="product-brand"></td>
        <td class="product-description"></td>
        <td>
        <button type="button" class="btn btn-danger btn-sm remove-product">
            <i class="fas fa-trash"></i>
        </button>
        </td>`;

            tbody.appendChild(tr);

            // Update brand and description on code selection change
            tr.querySelector('select').addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                var brand = selectedOption.getAttribute('data-brand');
                var description = selectedOption.getAttribute('data-description');

                tr.querySelector('.product-brand').textContent = brand;
                tr.querySelector('.product-description').textContent = description;
            });

            // Trigger change event to set initial brand and description
            tr.querySelector('select').dispatchEvent(new Event('change'));

            // Remove product row
            tr.querySelector('.remove-product').addEventListener('click', function () {
                tr.remove();
            });
        });

        // Handle form submission to update quantities and other invoice details
        document.getElementById('invoice-form').addEventListener('submit', function (e) {
            e.preventDefault();

            var quantities = document.querySelectorAll('input[name="quantity[]"]');
            var productCodes = document.querySelectorAll('select[name="product_code[]"]');
            var brands = document.querySelectorAll('.product-brand');
            var descriptions = document.querySelectorAll('.product-description');

            // Validate all products have a quantity and code selected
            if (quantities.length == 0 || Array.from(quantities).some(qty => !qty.value || qty.value <= 0) || Array.from(productCodes).some(code => !code.value)) {
                alert('Please fill in all product details (quantity and code).');
                return;
            }

            var productData = [];

            quantities.forEach(function (qtyInput, index) {
                var qty = qtyInput.value;
                var productId = productCodes[index].value;
                var brand = brands[index].textContent.trim();
                var description = descriptions[index].textContent.trim();

                productData.push({
                    product_id: productId,
                    quantity: qty,
                    brand: brand,
                    code: productCodes[index].options[productCodes[index].selectedIndex].text,
                    description: description
                });
            });

            // Send data to the backend to save the invoice and products
            $.ajax({
                url: "../../pages/invoice/ctrl-recieve/add-i-req-form.php",
                type: "POST",
                data: {
                    products: productData, // Send the products array to the backend
                    posting_date: $("#datepicker").val(),
                    delivery_date: $("#datepicker2").val(),
                    from_company_id: $("#company-from").val(),
                    to_company_id: $("#company-to").val(),
                    plant: $("#plant").val(),
                    po_number: $("#poNumber").val(),
                    reference_po: $("#reference-po").val(),
                    dr_number: $("#drNumber").val(),
                    plant_name: $("#plantName").val()
                },
                success: function (response) {
                    var result = JSON.parse(response); // Parse the JSON response from the server
                    if (result.status == 'success') {
                        alert(result.message);
                        window.location.href = "inv-request-form.php";  // Redirect to another page after success
                    } else {
                        alert(result.message);  // Show error message
                    }
                },
                error: function () {
                    alert("Error saving invoice.");
                }
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateCompanyDetails(selectElement, detailsElement) {
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var address = selectedOption.getAttribute("data-address");
            var attention = selectedOption.getAttribute("data-attention");
            var phone = selectedOption.getAttribute("data-phone");

            detailsElement.innerHTML = `
                <strong><i class="fas fa-building"></i> ${selectedOption.textContent}</strong><br>
                <i class="fas fa-map-marker-alt"></i> ${address}<br>
                <i class="fas fa-user"></i> ${attention}<br>
                <i class="fas fa-phone"></i> ${phone}
            `;
        }

        var companyFrom = document.getElementById("company-from");
        var companyFromDetails = document.getElementById("company-from-details");
        var companyTo = document.getElementById("company-to");
        var companyToDetails = document.getElementById("company-to-details");

        // Show details when the user clicks or changes the selection
        companyFrom.addEventListener("click", function () {
            updateCompanyDetails(companyFrom, companyFromDetails);
        });
        companyFrom.addEventListener("change", function () {
            updateCompanyDetails(companyFrom, companyFromDetails);
        });

        companyTo.addEventListener("click", function () {
            updateCompanyDetails(companyTo, companyToDetails);
        });
        companyTo.addEventListener("change", function () {
            updateCompanyDetails(companyTo, companyToDetails);
        });

        // Trigger the update on page load to display the first selected option
        updateCompanyDetails(companyFrom, companyFromDetails);
        updateCompanyDetails(companyTo, companyToDetails);
    });
</script>

<script>
    $(document).ready(function () {
        // When the "To Company" dropdown changes, update the plant and plant name fields
        $('#company-to').change(function () {
            var companyID = $(this).val();

            // Clear the plant field and plant name field
            $('#plant').val('');
            $('#plantName').val('');

            if (companyID) {
                // Find the selected company option
                var selectedCompany = $('#company-to option[value="' + companyID + '"]');

                // Get the plant and plant_name data attributes
                var plant = selectedCompany.data('plant');
                var plantName = selectedCompany.data('plant-name');

                // Set the plant and plant name fields
                $('#plant').val(plant);
                $('#plantName').val(plantName);

                // Optionally, populate company details (if you want to show the company's address, attention, etc.)
                var companyDetails = `
                <p><strong>Address:</strong> ${selectedCompany.data('address')}</p>
                <p><strong>Attention:</strong> ${selectedCompany.data('attention')}</p>
                <p><strong>Phone:</strong> ${selectedCompany.data('phone')}</p>
            `;
                $('#company-to-details').html(companyDetails);
            } else {
                // If no company selected, reset the plant and details fields
                $('#plant').val('');
                $('#plantName').val('');
                $('#company-to-details').empty();
            }
        });
    });
</script>