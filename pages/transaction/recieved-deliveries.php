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
                            <h1>Recieved Deliveries Page</h1>
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
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0" style="height: 300px;">
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>From Company</th>
                                                <th>To Company</th>
                                                <th>Delivery Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <?php
                                        include '../../includes/conn.php';

                                        $sql = "SELECT i.invoice_id, 
               fc.name AS from_company, 
               tc.name AS to_company, 
               d.date_value AS delivery_date
        FROM invoices i
        JOIN companies fc ON i.from_company_id = fc.company_id
        JOIN companies tc ON i.to_company_id = tc.company_id
        JOIN dates d ON i.delivery_date = d.date_id";

                                        $result = $conn->query($sql);

                                        if (!$result) {
                                            die("Query failed: " . $conn->error);  // This will print the error if the query fails
                                        }

                                        // Debugging: Check if any data is returned
                                        if ($result->num_rows == 0) {
                                            die("No invoices found in the database.");  // This will stop execution if no data is found
                                        }

                                        ?>

                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['invoice_id']) ?></td>
                                                        <td><?= htmlspecialchars($row['from_company']) ?></td>
                                                        <td><?= htmlspecialchars($row['to_company']) ?></td>
                                                        <td><?= htmlspecialchars($row['delivery_date']) ?></td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm">View</button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No invoices found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                        <!-- Main content -->
                        <section class="content">

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12">

                                        <!-- Default box -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Title</h3>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse" title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-tool" data-card-widget="remove"
                                                        title="Remove">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                Start creating your amazing
                                                application!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer">
                                                Footer
                                            </div>
                                            <!-- /.card-footer-->
                                        </div>
                                        <!-- /.card -->

                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- /.content -->

                    </div>

                </div><!-- /.container-fluid -->
                <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>