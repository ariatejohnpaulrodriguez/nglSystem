$(document).ready(function () {
    function updateCompanyDetails(selectElement, detailsElement) {
        if (!selectElement) {
            console.error("selectElement is undefined in updateCompanyDetails");
            return;
        }

        try {
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
        } catch (e) {
            console.error("Error in updateCompanyDetails:", e);
            detailsElement.innerHTML = "<p>Error loading company details.</p>";
        }
    }

    var companyFrom = $("#i-company-from");
    var companyFromDetails = $("#i-company-from-details");
    var companyTo = $("#i-company-to");
    var companyToDetails = $("#i-company-to-details");

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

    $("#i-datepicker, #i-datepicker2").datepicker({
        showAnim: "fadeIn",
        dateFormat: "yy-mm-dd"
    });

    $("#i-calendar-icon").click(function () {
        $("#i-datepicker").datepicker("show");
    });

    $("#i-calendar-icon2").click(function () {
        $("#i-datepicker2").datepicker("show");
    });

    function addProductRow() {
        var tbody = $("#i-product-table-body");
        var tr = $("<tr>");
    
        var quantityCell = $("<td>").append($("<input>", {
            type: "number",
            class: "form-control form-control-sm",
            name: "quantity[]",
            min: "1",
            value: "1"
        }));
    
        // Unit Dropdown
        var unitCell = $("<td>").append($("<select>", {
            class: "form-control form-control-sm",
            name: "unit[]"
        }).append($("<option>", {
            value: "",
            text: "Select Unit"
        })));
    
        var productCodeCell = $("<td>").append($("<select>", {
            class: "form-control form-control-sm",
            name: "product_code[]"
        }).append($("<option>", {
            value: "",
            text: "Select Code"
        })));
    
        var productBrandCell = $("<td class='product-brand'>");
        var productDescriptionCell = $("<td class='product-description'>");
    
        var removeButtonCell = $("<td>").append($("<button>", {
            type: "button",
            class: "btn btn-danger btn-sm remove-product"
        }).append($("<i>", {
            class: "fas fa-trash"
        })));
    
        tr.append(quantityCell, unitCell, productCodeCell, productBrandCell, productDescriptionCell, removeButtonCell);
        tbody.append(tr); // Append row first before making AJAX request
    
        // Fetch product list
        $.ajax({
            url: '../../pages/invoice/ctrl-receive/i-get-products.php',
            type: 'GET',
            dataType: 'json',
            success: function (products) {
                var productSelect = tr.find("select[name='product_code[]']");
                var unitSelect = tr.find("select[name='unit[]']");
    
                $.each(products, function (index, product) {
                    $("<option>", {
                        value: product.product_id,
                        text: product.code,
                        "data-brand": product.brand,
                        "data-description": product.description,
                        "data-unit": product.unit_name // Store unit name in data attribute
                    }).appendTo(productSelect);
                });
    
                // Fetch unit list separately
                $.ajax({
                    url: '../../pages/invoice/ctrl-receive/i-get-unit.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (units) {
                        $.each(units, function (index, unit) {
                            $("<option>", {
                                value: unit.unit_id,
                                text: unit.unit_name
                            }).appendTo(unitSelect);
                        });
                    },
                    error: function () {
                        console.error("Error fetching unit data.");
                    }
                });
    
                // Update Brand, Description, and Unit when a product is selected
                productSelect.change(function () {
                    var selectedOption = $(this).find("option:selected");
                    tr.find(".product-brand").text(selectedOption.data('brand'));
                    tr.find(".product-description").text(selectedOption.data('description'));
    
                    // Automatically set the unit dropdown based on selected product
                    unitSelect.val(selectedOption.data('unit'));
                });
    
                productSelect.trigger('change'); // Trigger change to populate initial values
            },
            error: function () {
                console.error("Error fetching product data.");
            }
        });
    
        // Remove row on delete button click
        removeButtonCell.click(function () {
            tr.remove();
        });
    }
    
    $("#i-add-products").click(addProductRow);
    
    // Include the unit ID when constructing product data for submission
    $("#invoice-form").submit(function (e) {
        e.preventDefault();
    
        var quantities = $("input[name='quantity[]']");
        var productCodes = $("select[name='product_code[]']");
        var units = $("select[name='unit[]']");
    
        var hasErrors = false;
        quantities.each(function (index) {
            var qty = $(this).val();
            var code = productCodes.eq(index).val();
            var unit = units.eq(index).val();
    
            if (!qty || qty <= 0 || !code || !unit) {
                hasErrors = true;
                return false;
            }
        });
    
        if (hasErrors) {
            toastr.error('Please fill in all product details (quantity, code, and unit).');
            return;
        }
    
        var productData = [];
    
        quantities.each(function (index) {
            var qty = $(this).val();
            var productId = productCodes.eq(index).val();
            var unitId = units.eq(index).val();
            var brand = $(this).closest("tr").find(".product-brand").text().trim();
            var description = $(this).closest("tr").find(".product-description").text().trim();
    
            productData.push({
                product_id: productId,
                quantity: qty,
                unit_id: unitId, // Include unit ID in payload
                brand: brand,
                code: productCodes.eq(index).find("option:selected").text(),
                description: description,
            });
        });
    
        var data = {
            products: productData,
            posting_date: $("#i-datepicker").val(),
            delivery_date: $("#i-datepicker2").val(),
            from_company_id: $("#i-company-from").val(),
            to_company_id: $("#i-company-to").val(),
            po_number: $("#i-poNumber").val(),
            reference_po: $("#i-reference-po").val(),
            dr_number: $("#i-drNumber").val()
        };
    
        console.log("Data being sent:", data);
    
        $.ajax({
            url: "../../pages/invoice/ctrl-receive/save-invoice.php",
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    window.location.href = "inv-request-form.php";
                } else {
                    console.error("Error saving invoice:", response.message);
                    toastr.error("Error saving invoice: " + response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error saving invoice:", textStatus, errorThrown, jqXHR.responseText);
                let errorMessage = "Error saving invoice: " + textStatus;
                if (jqXHR.status === 0) {
                    errorMessage += ". Possible CORS issue or server is down.";
                }
                toastr.error(errorMessage);
            }
        });
    });

    $("#i-company-to").change(function () {
        var companyID = $(this).val();

        $("#i-plant").val('');
        $("#i-plantName").val('');

        if (companyID) {
            var selectedCompany = $("#i-company-to option[value='" + companyID + "']");

            var plant = selectedCompany.data('plant');
            var plantName = selectedCompany.data('plant-name');

            $("#i-plant").val(plant);
            $("#i-plantName").val(plantName);

            var companyDetails = `
                <p><strong>Address:</strong> ${selectedCompany.data('address')}</p>
                <p><strong>Attention:</strong> ${selectedCompany.data('attention')}</p>
                <p><strong>Phone:</strong> ${selectedCompany.data('phone')}</p>
            `;
            $("#i-company-to-details").html(companyDetails);
        } else {
            $("#i-plant").val('');
            $("#i-plantName").val('');
            $("#i-company-to-details").empty();
        }
    });

    $(document).on('click', '.view-btn', function () {
        let invoiceId = $(this).data('id');
        let status = $(this).data('status');
    
        $('#viewModal').modal('show');
    
        $('#from-info, #to-info, #invoice-info, #dr-info, #po-info, #reference-info, #posting-date-info, #delivery-date-info').html('<p>Loading...</p>');
        $('#product-list').html('<tr><td colspan="5">Loading products...</td></tr>'); // Changed colspan to 5
    
        $.ajax({
            url: '../../pages/invoice/ctrl-receive/view-invoice-status.php',
            type: 'GET',
            data: { invoice_id: invoiceId },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    $('#from-info, #to-info, #invoice-info, #dr-info, #po-info, #reference-info, #posting-date-info, #delivery-date-info').html('<p>No data found.</p>');
                    $('#product-list').html('<tr><td colspan="5">No products found.</td></tr>'); // Changed colspan to 5
                    $('#status-banner').html('');
    
                    // Clear existing content
                    $('#from-info').empty();
                    $('#to-info').empty();
                    $('#invoice-info').empty();
                    $('#dr-info').empty();
                    $('#po-info').empty();
                    $('#reference-info').empty();
                    $('#posting-date-info').empty();
                    $('#delivery-date-info').empty();
                    $('#product-list').empty();
                    $('#status-banner').empty();
                } else {
                    let invoice = response.invoice;
                    let products = response.products;
    
                    $('#from-info').html(`
                        <strong><i class="fas fa-building"></i> ${invoice.from_company_name}</strong><br>
                        <i class="fas fa-map-marker-alt"></i> ${invoice.from_company_address}<br>
                        <i class="fas fa-phone"></i> ${invoice.from_company_phone}<br>
                        <i class="fas fa-user"></i> ${invoice.from_company_attention}
                    `);
    
                    $('#to-info').html(`
                        <strong><i class="fas fa-building"></i> ${invoice.to_company_name}</strong><br>
                        <i class="fas fa-map-marker-alt"></i> ${invoice.to_company_address}<br>
                        <i class="fas fa-phone"></i> ${invoice.to_company_phone}<br>
                        <i class="fas fa-user"></i> ${invoice.to_company_attention}
                    `);
    
                    $('#invoice-info').html(`<b>Invoice No:</b> ${invoice.invoice_id}`);
                    $('#dr-info').html(`<b>DR No:</b> ${invoice.dr_number}`);
                    $('#po-info').html(`<b>PO No:</b> ${invoice.po_number}`);
                    $('#reference-info').html(`<b>Reference No:</b> ${invoice.reference_po_number}`);
                    $('#posting-date-info').html(`<b>Posting Date:</b> ${invoice.posting_date}`);
                    $('#delivery-date-info').html(`<b>Delivery Date:</b> ${invoice.delivery_date}`);
    
                    let productHtml = '';
                    if (products.length > 0) {
                        products.forEach(product => {
                            productHtml += `
                                <tr>
                                    <td>${product.quantity}</td>
                                    <td>${product.code}</td>
                                    <td>${product.brand}</td>
                                    <td>${product.description}</td>
                                    <td>${product.unit_name}</td>
                                </tr>
                            `;
                        });
                    } else {
                        productHtml = '<tr><td colspan="5">No products found.</td></tr>'; // Changed colspan to 5
                    }
                    $('#product-list').html(productHtml);
    
                    let bannerText = invoice.status_name;
                    let bannerClass = '';
    
                    switch (bannerText) {
                        case 'Approved':
                            bannerClass = 'bg-success';
                            break;
                        case 'Rejected':
                            bannerClass = 'bg-danger';
                            break;
                        case 'Pending':
                            bannerClass = 'bg-warning';
                            break;
                        case 'Cancelled':
                            bannerClass = 'bg-dark';
                            break;
                        default:
                            bannerClass = 'bg-secondary';
                            break;
                    }
    
                    $('#status-banner').html(`
                        <div class="ribbon-wrapper">
                            <div class="ribbon ${bannerClass}">
                                ${bannerText}
                            </div>
                        </div>
                    `);
    
                    var fromCompanyValue = invoice.from_company_name;
                    var toCompanyValue = invoice.to_company_name;
    
                    // Set company values and trigger change events
                    $('#i-company-from').val(fromCompanyValue).trigger('change');
                    $('#i-company-to').val(toCompanyValue).trigger('change');
                }
            },
            error: function () {
                $('#from-info, #to-info, #invoice-info, #dr-info, #po-info, #reference-info, #posting-date-info, #delivery-date-info').html('<p>Error loading data.</p>');
                $('#product-list').html('<tr><td colspan="5">Error loading products.</td></tr>'); // Changed colspan to 5
                $('#status-banner').html('');
            }
        });
    });
    
    // Initialize DataTable
    $('#invoiceTables').DataTable({
        "paging": false,           // Enable pagination
        "lengthChange": false,    // Disable page size change
        "searching": false,        // Enable search box
        "ordering": true,         // Enable sorting
        "info": false,             // Show table info
        "autoWidth": false,       // Disable auto column width
        "order": [[0, 'desc']],   // Default sort by Invoice ID (Descending)
        "columnDefs": [
            { "orderable": false, "targets": [4] } // Disable sorting for the "Action" column
        ]
    });
});