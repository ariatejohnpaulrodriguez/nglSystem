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

    $("#datepicker, #datepicker2").datepicker({
        showAnim: "fadeIn",
        dateFormat: "yy-mm-dd"
    });

    $("#calendar-icon").click(function () {
        $("#datepicker").datepicker("show");
    });

    $("#calendar-icon2").click(function () {
        $("#datepicker2").datepicker("show");
    });

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

        tr.append(quantityCell, productCodeCell, productBrandCell, productDescriptionCell, removeButtonCell);

        $.ajax({
            url: '../../pages/invoice/ctrl-recieve/get-products.php',
            type: 'GET',
            dataType: 'json',
            success: function (products) {
                var selectElement = tr.find('select');
                $.each(products, function (index, product) {
                    $("<option>", {
                        value: product.product_id,
                        text: product.code,
                        "data-brand": product.brand,
                        "data-description": product.description
                    }).appendTo(selectElement);
                });

                selectElement.change(function () {
                    var selectedOption = $(this).find("option:selected");
                    var brand = selectedOption.data('brand');
                    var description = selectedOption.data('description');

                    productBrandCell.text(brand);
                    productDescriptionCell.text(description);
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

    $("#add-products").click(addProductRow);

    $("#invoice-form").submit(function (e) {
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
            posting_date: $("#datepicker").val(),
            delivery_date: $("#datepicker2").val(),
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
            url: "../../pages/invoice/ctrl-recieve/save-invoice.php",
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.href = "inv-request-form.php";
                } else {
                    console.error("Error saving invoice:", response.message);
                    alert("Error saving invoice: " + response.message + ". Check console for details.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error saving invoice:", textStatus, errorThrown, jqXHR.responseText);
                alert("Error saving invoice. Check console for details.");
            }
        });
    });

    $("#company-to").change(function () {
        var companyID = $(this).val();

        $("#plant").val('');
        $("#plantName").val('');

        if (companyID) {
            var selectedCompany = $("#company-to option[value='" + companyID + "']");

            var plant = selectedCompany.data('plant');
            var plantName = selectedCompany.data('plant-name');

            $("#plant").val(plant);
            $("#plantName").val(plantName);

            var companyDetails = `
                <p><strong>Address:</strong> ${selectedCompany.data('address')}</p>
                <p><strong>Attention:</strong> ${selectedCompany.data('attention')}</p>
                <p><strong>Phone:</strong> ${selectedCompany.data('phone')}</p>
            `;
            $("#company-to-details").html(companyDetails);
        } else {
            $("#plant").val('');
            $("#plantName").val('');
            $("#company-to-details").empty();
        }
    });

    $(document).on('click', '.view-btn', function () {
        let invoiceId = $(this).data('id');
        let status = $(this).data('status');

        $('#viewModal').modal('show');

        $('#from-info, #to-info, #invoice-info, #dr-info, #po-info, #reference-info, #posting-date-info, #delivery-date-info').html('<p>Loading...</p>');
        $('#product-list').html('<tr><td colspan="4">Loading products...</td></tr>');

        $.ajax({
            url: '../../pages/invoice/ctrl-recieve/view-invoice-status.php',
            type: 'GET',
            data: { invoice_id: invoiceId },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    $('#from-info, #to-info, #invoice-info, #dr-info, #po-info, #reference-info, #posting-date-info, #delivery-date-info').html('<p>No data found.</p>');
                    $('#product-list').html('<tr><td colspan="4">No products found.</td></tr>');
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
                                </tr>
                            `;
                        });
                    } else {
                        productHtml = '<tr><td colspan="4">No products found.</td></tr>';
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
                    $('#company-from').val(fromCompanyValue).trigger('change');
                    $('#company-to').val(toCompanyValue).trigger('change');
                }
            },
            error: function () {
                $('#from-info, #to-info, #invoice-info, #dr-info, #po-info, #reference-info, #posting-date-info, #delivery-date-info').html('<p>Error loading data.</p>');
                $('#product-list').html('<tr><td colspan="4">Error loading products.</td></tr>');
                $('#status-banner').html('');
            }
        });
    });

    $(document).ready(function () {
        $(document).on('click', '.approve-btn, .reject-btn, .pending-btn, .cancel-btn', function () {
            let invoiceID = $(this).data('id');
            let action = $(this).hasClass('approve-btn') ? 'Approve' :
                $(this).hasClass('reject-btn') ? 'Reject' :
                    $(this).hasClass('pending-btn') ? 'Pending' : 'Cancelled';

            if (!confirm(`Are you sure you want to mark this invoice as ${action}?`)) return;

            let $button = $(this);
            $button.prop('disabled', true);

            $.ajax({
                url: '../../pages/invoice/ctrl-recieve/update-invoice-status.php',
                type: 'POST',
                data: { invoice_id: invoiceID, action: action },
                dataType: 'json',
            })
                .done(function (response) {
                    console.log('Server Response:', response);

                    if (response.status === 'success') {
                        alert(response.message);
                        location.reload();
                    } else {
                        console.error('Server Error:', response.message);
                        alert("Error: " + response.message);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
                    alert("An unexpected error occurred. Check console for details.");
                })
                .always(function () {
                    $button.prop('disabled', false);
                });
        });
    });
});