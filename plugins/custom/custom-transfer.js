$(document).ready(function () {
    // Function: updateCompanyDetails - No changes needed (can be reused)
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

    // Company Selection - Reuse updateCompanyDetails
    var companyFrom = $("#company-from");
    var companyFromDetails = $("#company-from-details");
    var companyTo = $("#company-to");
    var companyToDetails = $("#company-to-details");

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

    // Datepicker - Update Selectors to #datepicker3 and #datepicker4
    $("#datepicker3, #datepicker4").datepicker({ // UPDATED SELECTORS
        showAnim: "fadeIn",
        dateFormat: "yy-mm-dd"
    });

    $("#calendar-icon").click(function () { // Keep
        $("#datepicker3").datepicker("show"); // Updated
    });

    $("#calendar-icon2").click(function () { // Keep
        $("#datepicker4").datepicker("show"); // Updated
    });

    // Function: addProductRow - Major Changes Needed
    function addProductRow() {
        var tbody = $("#product-table-body");
        var tr = $("<tr>");

        var quantityCell = $("<td>").append($("<input>", {
            type: "number",
            class: "form-control form-control-sm",
            name: "quantity[]",
            min: "1",
            value: "1"
        }));

        // Add Stock Column
        var stockCell = $("<td>", { class: 'product-stock' }).text('Loading...');

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

        tr.append(quantityCell, stockCell, productCodeCell, productBrandCell, productDescriptionCell, removeButtonCell);

        $.ajax({
            url: '../../pages/transfer/ctrl-transfer/get-products.php', // Keep, unless you want to seperate
            type: 'GET',
            dataType: 'json',
            success: function (products) {
                var selectElement = tr.find('select');
                $.each(products, function (index, product) {
                    $("<option>", {
                        value: product.product_id,
                        text: product.code,
                        "data-brand": product.brand,
                        "data-description": product.description,
                        "data-stock": product.current_quantity
                    }).appendTo(selectElement);
                });

                selectElement.change(function () {
                    var selectedOption = $(this).find("option:selected");
                    var brand = selectedOption.data('brand');
                    var description = selectedOption.data('description');

                    productBrandCell.text(brand);
                    productDescriptionCell.text(description);
                });

                //Get the stocks
                selectElement.change(function () {
                    var selectedProductId = $(this).val(); // Get the selected product ID
                    if (selectedProductId) {
                        $.ajax({
                            url: '../../pages/transfer/ctrl-transfer/get-stock.php', // Create a new php file called get-stock.php
                            type: 'GET',
                            dataType: 'json',
                            data: { product_id: selectedProductId },
                            success: function (stockData) {
                                stockCell.text(stockData.current_quantity);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.error("Error fetching stock data:", textStatus, errorThrown);
                                stockCell.text('Error');
                            }
                        });
                    } else {
                        stockCell.text('N/A');
                    }
                });

                selectElement.trigger('change');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error fetching product data:", textStatus, errorThrown);
                alert('Error fetching product data. Check console for details.');
            }
        });

        removeButtonCell.click(function () {
            tr.remove();
        });

        tbody.append(tr);
    }

    // Update add-products to add-t-products
    $("#add-t-products").click(addProductRow); // UPDATED SELECTOR

    // Form Submission - Update #invoice-form to #transfer-form and URLs
    $("#transfer-form").submit(function (e) { // UPDATED SELECTOR
        e.preventDefault();

        var quantities = $("input[name='quantity[]']");
        var productCodes = $("select[name='product_code[]']");

        var hasErrors = false;
        quantities.each(function (index) {
            var qty = $(this).val();
            var code = productCodes.eq(index).val();

            if (!qty || qty <= 0 || !code) {
                hasErrors = true;
                return false;
            }
        });

        if (hasErrors) {
            alert('Please fill in all product details (quantity and code).');
            return;
        }

        var productData = [];

        quantities.each(function (index) {
            var qty = $(this).val();
            var productId = productCodes.eq(index).val();
            var brand = $(this).closest("tr").find(".product-brand").text().trim();
            var description = $(this).closest("tr").find(".product-description").text().trim();

            productData.push({
                product_id: productId,
                quantity: qty,
                brand: brand,
                code: productCodes.eq(index).find("option:selected").text(),
                description: description
            });
        });

        var data = {
            products: productData,
            posting_date: $("#datepicker3").val(), // UPDATED SELECTOR
            delivery_date: $("#datepicker4").val(), // UPDATED SELECTOR
            from_company_id: $("#company-from").val(),
            to_company_id: $("#company-to").val(),
            plant: $("#plant").val(),
            po_number: $("#poNumber").val(),
            reference_po: $("#reference-po").val(),
            dr_number: $("#drNumber").val(),
            plant_name: $("#plantName").val(),
            status_id: $("#status").val()
        };

        $.ajax({
            url: "../../pages/transfer/ctrl-transfer/save-transfer.php", // UPDATED URL
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.href = "transfer-request-form.php"; // UPDATED URL
                } else {
                    console.error("Error saving transfer:", response.message);
                    alert("Error saving transfer: " + response.message + ". Check console for details.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error saving transfer:", textStatus, errorThrown, jqXHR.responseText);
                alert("Error saving transfer. Check console for details.");
            }
        });
    });
    
    $("#company-from").change(function () { //Updated selector to company-from
        var companyID = $(this).val();

        $("#plant").val('');
        $("#plantName").val('');

        if (companyID) {
            var selectedCompany = $("#company-from option[value='" + companyID + "']"); //Updated selector to company-from

            var plant = selectedCompany.data('plant');
            var plantName = selectedCompany.data('plant-name');

            $("#plant").val(plant);
            $("#plantName").val(plantName);

            var companyDetails = `
                <p><strong>Address:</strong> ${selectedCompany.data('address')}</p>
                <p><strong>Attention:</strong> ${selectedCompany.data('attention')}</p>
                <p><strong>Phone:</strong> ${selectedCompany.data('phone')}</p>
            `;
            $("#company-from-details").html(companyDetails); //Updated selector to company-from
        } else {
            $("#plant").val('');
            $("#plantName").val('');
            $("#company-from-details").empty(); //Updated selector to company-from
        }
    });

    //View transfer and get the values. The  $(document).on('click', '.view-btn' is in transaction-deliveries.php
    $(document).on('click', '.view-btn-transfer', function () {
        let transferId = $(this).data('id');

        $('#viewModal-transfer').modal('show');

        $('#from-info-transfer, #to-info-transfer, #transfer-info-transfer, #dr-info-transfer, #po-info-transfer, #reference-info-transfer, #posting-date-info-transfer, #delivery-date-info-transfer').html('<p>Loading...</p>');
        $('#product-list-transfer').html('<tr><td colspan="4">Loading products...</td></tr>');

        $.ajax({
            url: '../../pages/transfer/ctrl-transfer/view-transfer-status.php',
            type: 'GET',
            data: { transfer_id: transferId },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    $('#from-info-transfer, #to-info-transfer, #transfer-info-transfer, #dr-info-transfer, #po-info-transfer, #reference-info-transfer, #posting-date-info-transfer, #delivery-date-info-transfer').html('<p>No data found.</p>');
                    $('#product-list-transfer').html('<tr><td colspan="4">No products found.</td></tr>');
                    $('#status-banner-transfer').html('');

                    // Clear existing content
                    $('#from-info-transfer').empty();
                    $('#to-info-transfer').empty();
                    $('#transfer-info-transfer').empty();
                    $('#dr-info-transfer').empty();
                    $('#po-info-transfer').empty();
                    $('#reference-info-transfer').empty();
                    $('#posting-date-info-transfer').empty();
                    $('#delivery-date-info-transfer').empty();
                    $('#product-list-transfer').empty();
                    $('#status-banner-transfer').empty();
                } else {
                    let transfer = response.transfer;
                    let products = response.products;

                    $('#from-info-transfer').html(`
                        <strong><i class="fas fa-building"></i> ${transfer.from_company_name}</strong><br>
                        <i class="fas fa-map-marker-alt"></i> ${transfer.from_company_address}<br>
                        <i class="fas fa-phone"></i> ${transfer.from_company_phone}<br>
                        <i class="fas fa-user"></i> ${transfer.from_company_attention}
                    `);

                    $('#to-info-transfer').html(`
                        <strong><i class="fas fa-building"></i> ${transfer.to_company_name}</strong><br>
                        <i class="fas fa-map-marker-alt"></i> ${transfer.to_company_address}<br>
                        <i class="fas fa-phone"></i> ${transfer.to_company_phone}<br>
                        <i class="fas fa-user"></i> ${transfer.to_company_attention}
                    `);

                    $('#transfer-info-transfer').html(`<b>Transfer No:</b> ${transfer.transfer_id}`);
                    $('#dr-info-transfer').html(`<b>DR No:</b> ${transfer.dr_number}`);
                    $('#po-info-transfer').html(`<b>PO No:</b> ${transfer.po_number}`);
                    $('#reference-info-transfer').html(`<b>Reference No:</b> ${transfer.reference_po_number}`);
                    $('#posting-date-info-transfer').html(`<b>Posting Date:</b> ${transfer.posting_date}`);
                    $('#delivery-date-info-transfer').html(`<b>Delivery Date:</b> ${transfer.delivery_date}`);

                    let productHtml = '';
                    if (products.length > 0) {
                        products.forEach(product => {
                            productHtml += `
                                <tr>
                                    <td>${product.quantity}</td>
                                    <td>${product.code}</td>
                                    <td>${product.brand}</td>
                                    <td>${product.description}</td>
                                </tr>
                            `;
                        });
                    } else {
                        productHtml = '<tr><td colspan="4">No products found.</td></tr>';
                    }
                    $('#product-list-transfer').html(productHtml);

                    let bannerText = transfer.status_name;
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

                    $('#status-banner-transfer').html(`
                        <div class="ribbon-wrapper">
                            <div class="ribbon ${bannerClass}">
                                ${bannerText}
                            </div>
                        </div>
                    `);
                }
            },
            error: function () {
                $('#from-info-transfer, #to-info-transfer, #transfer-info-transfer, #dr-info-transfer, #po-info-transfer, #reference-info-transfer, #posting-date-info-transfer, #delivery-date-info-transfer').html('<p>Error loading data.</p>');
                $('#product-list-transfer').html('<tr><td colspan="4">Error loading products.</td></tr>');
                $('#status-banner-transfer').html('');
            }
        });
    });

    $(document).ready(function () {
        $(document).on('click', '.approve-btn, .reject-btn, .pending-btn, .cancel-btn', function () {
            let transferID = $(this).data('id');
            let action = $(this).hasClass('approve-btn') ? 'Approve' :
                $(this).hasClass('reject-btn') ? 'Reject' :
                    $(this).hasClass('pending-btn') ? 'Pending' : 'Cancelled';

            if (!confirm(`Are you sure you want to mark this transfer as ${action}?`)) return;

            let $button = $(this);
            $button.prop('disabled', true);

            $.ajax({
                url: '../../pages/transfer/ctrl-transfer/update-transfer-status.php',
                type: 'POST',
                data: { transfer_id: transferID, action: action },
                dataType: 'json',
                success: function (response) {
                    console.log('Server Response:', response);

                    if (response.status === 'success') {
                        alert(response.message);
                        location.reload();
                    } else {
                        console.error('Server Error:', response.message);
                        alert("Error: " + response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
                    alert("An unexpected error occurred. Check console for details.");
                },
                complete: function () {
                    $button.prop('disabled', false);
                }
            });
        });
    });
});