<?php
include '../../includes/header.php';
include '../../includes/session.php';
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
                            <h1>Transfer Delivery Status</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">List</h3>

                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="table_search" class="form-control float-right"
                                                placeholder="Search">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body table-responsive p-0" style="height: 500px;">
                                    <table id="transferTable" class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Transfer ID</th>
                                                <th>From Company</th>
                                                <th>To Company</th>
                                                <th>Delivery Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include '../../includes/conn.php';

                                            $sql = "SELECT t.transfer_id,
                                                    fc.name AS from_company,
                                                    tc.name AS to_company,
                                                    d.date_value AS delivery_date,
                                                    s.status_name
                                                    FROM transfers t
                                                    JOIN companies fc ON t.from_company_id = fc.company_id
                                                    JOIN companies tc ON t.to_company_id = tc.company_id
                                                    JOIN dates d ON t.delivery_date = d.date_id
                                                    JOIN statuses s ON t.status_id = s.status_id";

                                            $stmt = $conn->prepare($sql);
                                            if (!$stmt) {
                                                error_log("Prepare failed: " . $conn->error);
                                                echo "<tr><td colspan='6' class='text-center'>Error fetching transfers. Please check the logs.</td></tr>";
                                            } else {
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if (!$result) {
                                                    error_log("Query failed: " . $stmt->error);
                                                    echo "<tr><td colspan='6' class='text-center'>Error fetching transfers. Please check the logs.</td></tr>";
                                                } else {
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $transfer_id = htmlspecialchars($row['transfer_id']);
                                                            $from_company = htmlspecialchars($row['from_company']);
                                                            $to_company = htmlspecialchars($row['to_company']);
                                                            $delivery_date = htmlspecialchars($row['delivery_date']);
                                                            $status = htmlspecialchars($row['status_name']);

                                                            $statusClass = '';
                                                            switch ($status) {
                                                                case 'Approved':
                                                                    $statusClass = 'bg-success text-white';
                                                                    break;
                                                                case 'Rejected':
                                                                    $statusClass = 'bg-danger text-white';
                                                                    break;
                                                                case 'Pending':
                                                                    $statusClass = 'bg-warning text-white';
                                                                    break;
                                                                case 'Cancelled':
                                                                    $statusClass = 'bg-dark text-white';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'bg-secondary text-white';
                                                                    break;
                                                            }

                                                            echo "<tr>";
                                                            echo "<td>{$transfer_id}</td>";
                                                            echo "<td>{$from_company}</td>";
                                                            echo "<td>{$to_company}</td>";
                                                            echo "<td>{$delivery_date}</td>";
                                                            echo "<td><span class='badge {$statusClass}'>{$status}</span></td>";
                                                            echo "<td>";
                                                            if ($_SESSION['role'] == 'President') {
                                                                echo "<button class='btn btn-primary btn-sm view-btn-transfer mr-1' data-id='{$transfer_id}' data-status='{$status}' data-toggle='modal' data-target='#viewModal-transfer'>View</button>";
                                                                echo "<button class='btn btn-success btn-sm approve-btn mr-1' data-id='{$transfer_id}'>Approve</button>";
                                                                echo "<button class='btn btn-danger btn-sm reject-btn mr-1' data-id='{$transfer_id}'>Reject</button>";
                                                                echo "<button class='btn btn-warning btn-sm pending-btn mr-1' data-id='{$transfer_id}'>Pending</button>";
                                                                echo "<button class='btn btn-dark btn-sm cancel-btn mr-1' data-id='{$transfer_id}'>Cancelled</button>";
                                                            } else {
                                                                echo "<button class='btn btn-primary btn-sm view-btn-transfer mr-1' data-id='{$transfer_id}' data-status='{$status}' data-toggle='modal' data-target='#viewModal-transfer'>View</button>";
                                                            }
                                                            echo "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6' class='text-center'>No transfers found</td></tr>";
                                                    }
                                                }
                                                $stmt->close();
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal (Pop-up) -->
            <div class="modal fade" id="viewModal-transfer" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">Transfer Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="content">
                                <section class="content mt-3">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="position-relative">
                                                    <div id="status-banner-transfer"></div>
                                                    <!-- The banner status will display here -->
                                                    <div class="callout callout-info">
                                                        <h5><i class="fas fa-info"></i> Note:</h5>
                                                        This page has been processed by Warehouse Staff, requesting
                                                        for
                                                        confirmation.
                                                    </div>
                                                </div>

                                                <!-- Main content -->
                                                <div class="transfer p-3 mb-3">
                                                    <!-- title row -->
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h4>
                                                                <i class="fas fa-globe"></i> New Generation Link,
                                                                Inc.
                                                            </h4>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- info row -->
                                                    <div class="row transfer-info">
                                                        <div class="col-sm-4 transfer-col">
                                                            From
                                                            <address id="from-info-transfer">
                                                                <p>Loading from data...</p>
                                                                <!-- Data will be inserted here -->
                                                            </address>
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-4 transfer-col">
                                                            To
                                                            <address id="to-info-transfer">
                                                                <p>Loading to data...</p>
                                                                <!-- Data will be inserted here -->
                                                            </address>
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-4 transfer-col">
                                                            <span id="transfer-info-transfer">Loading transfer
                                                                data...</span><br>
                                                            <span id="dr-info-transfer">Loading dr data...</span><br>
                                                            <span id="po-info-transfer">Loading po data...</span><br>
                                                            <span id="reference-info-transfer">Loading ref
                                                                data...</span><br>
                                                            <span id="posting-date-info-transfer">Loading posting date
                                                                data...</span><br>
                                                            <span id="delivery-date-info-transfer">Loading delivery date
                                                                data...</span><br>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->

                                                    <!-- Table row -->
                                                    <div class="row">
                                                        <div class="col-12 table-responsive">
                                                            <table id="transferTable" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Quantity</th>
                                                                        <th>Code</th>
                                                                        <th>Brand</th>
                                                                        <th>Description</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="product-list-transfer">
                                                                    <tr>
                                                                        <td colspan="4">No products found.</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->

                                                    <!-- this row will not appear when printing -->
                                                    <div class="row no-print">
                                                        <div class="col-12">
                                                            <a href="transfer-print.php" rel="noopener" target="_blank"
                                                                class="btn btn-default"><i class="fas fa-print"></i>
                                                                Print</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.transfer -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.container-fluid -->
                                </section>
                                <!-- /.content -->
                            </div>
                            <!-- /.content-wrapper -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal -->

        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>