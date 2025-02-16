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

                            <!-- Main content -->
                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row">
                                    <div class="col-12">
                                        <h4>
                                            <i class="fas fa-file-invoice"></i> Invoice Information
                                            <small class="float-right">
                                                Date:
                                                <input type="text" id="datepicker" class="form-control form-control-sm"
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

                                    <script>
                                        // Update "From" company details on selection change
                                        document.getElementById('company-from').addEventListener('change', function () {
                                            var selectedOption = this.options[this.selectedIndex];
                                            var address = selectedOption.getAttribute('data-address');
                                            var phone = selectedOption.getAttribute('data-phone');
                                            var email = selectedOption.getAttribute('data-email');

                                            var companyDetails = `
                <strong><i class="fas fa-building"></i> ${selectedOption.textContent}</strong><br>
                <i class="fas fa-map-marker-alt"></i> ${address}<br>
                <i class="fas fa-phone"></i> ${phone}<br>
                <i class="fas fa-envelope"></i> ${email}
            `;
                                            document.getElementById('company-from-details').innerHTML = companyDetails;
                                        });

                                        // Update "To" company details on selection change
                                        document.getElementById('company-to').addEventListener('change', function () {
                                            var selectedOption = this.options[this.selectedIndex];
                                            var address = selectedOption.getAttribute('data-address');
                                            var phone = selectedOption.getAttribute('data-phone');
                                            var email = selectedOption.getAttribute('data-email');

                                            var companyDetails = `
                <strong><i class="fas fa-building"></i> ${selectedOption.textContent}</strong><br>
                <i class="fas fa-map-marker-alt"></i> ${address}<br>
                <i class="fas fa-phone"></i> ${phone}<br>
                <i class="fas fa-envelope"></i> ${email}
            `;
                                            document.getElementById('company-to-details').innerHTML = companyDetails;
                                        });

                                        // Trigger change events on page load to show details of the first company in both dropdowns
                                        document.getElementById('company-from').dispatchEvent(new Event('change'));
                                        document.getElementById('company-to').dispatchEvent(new Event('change'));
                                    </script>

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


                                <form id="invoice-form" method="post">
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
                                </form>

                                <script>
                                    document.getElementById('add-product').addEventListener('click', function () {
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

                                    // Handle form submission to update quantities
                                    document.getElementById('invoice-form').addEventListener('submit', function (e) {
                                        e.preventDefault();

                                        var quantities = document.querySelectorAll('input[name="quantity[]"]');
                                        var productCodes = document.querySelectorAll('select[name="product_code[]"]');

                                        quantities.forEach(function (qtyInput, index) {
                                            var qty = qtyInput.value;
                                            var productId = productCodes[index].value;

                                            console.log('Sending request for product ID: ' + productId + ' with quantity: ' + qty); // Add logging

                                            // Update the quantity in the database
                                            var xhr = new XMLHttpRequest();
                                            xhr.open('POST', 'recieve/delivered-recieve.php', true);
                                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                            xhr.send('product_id=' + productId + '&quantity=' + qty);

                                            xhr.onreadystatechange = function () {
                                                if (xhr.readyState == 4) {
                                                    if (xhr.status == 200) {
                                                        console.log('Response: ' + xhr.responseText);

                                                        var response = JSON.parse(xhr.responseText);

                                                        // Show success or error notification
                                                        if (response.status === 'success') {
                                                            alert(response.message);
                                                        } else {
                                                            alert('Error: ' + response.message);
                                                        }
                                                    } else {
                                                        console.error('Error: ' + xhr.status);
                                                    }
                                                }
                                            };
                                        });
                                    });
                                </script>
                            </div>
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