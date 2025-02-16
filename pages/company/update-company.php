<?php
include '../../includes/header.php';
include '../../includes/session.php';
?>

<link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="../../../dist/css/adminlte.min.css">

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include '../../includes/navbar.php'; ?>
        <!-- /.navbar -->
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Update Company</h1>
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
                        <div class="col-md-6">
                            <!-- Form Element sizes -->
                            <div class="card card-secondary">
                                <?php
                                include '../../includes/conn.php';

                                // Initialize variables
                                $company_id = '';
                                $name = '';
                                $address = '';
                                $phoneNumber = '';
                                $email = '';
                                $plant = '';
                                $plant_name = '';
                                $attention = '';

                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $company_id = $_POST['company_id'];

                                    // Fetch the president data based on the ID
                                    $query = "SELECT name, address, phone_number, email, plant, plant_name, attention FROM companies WHERE company_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $company_id);
                                    $stmt->execute();
                                    $stmt->bind_result($name, $address, $phoneNumber, $email, $plant, $plant_name, $attention);
                                    $stmt->fetch();
                                    $stmt->close();
                                    $conn->close();
                                }
                                ?>
                                <div class="card-header">
                                    <h5 class="card-title">Change Company Information</h5>
                                </div>
                                <form action="ctrl-company/update-ctrl-company.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-6 d-flex flex-column">
                                                <div class="form-group">
                                                    <label for="company_id">Company ID</label>
                                                    <input class="form-control" type="text" id="company_id"
                                                        name="company_id"
                                                        value="<?php echo htmlspecialchars($company_id); ?>" required
                                                        readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="companyName">Company Name</label>
                                                    <input class="form-control" type="text" id="companyName"
                                                        name="companyName"
                                                        value="<?php echo htmlspecialchars($name); ?>"
                                                        placeholder="Enter Company Name">
                                                </div>

                                                <div class="form-group">
                                                    <label for="companyAddress">Address</label>
                                                    <textarea class="form-control" id="companyAddress"
                                                        name="companyAddress" placeholder="Enter Address"
                                                        required><?php echo htmlspecialchars($address); ?></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="companyPhoneNumber">Phone Number</label>
                                                    <input class="form-control" type="text" id="companyPhoneNumber"
                                                        name="companyPhoneNumber"
                                                        value="<?php echo htmlspecialchars($phoneNumber); ?>"
                                                        placeholder="Enter Phone Number" required>
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="col-md-6 d-flex flex-column">
                                                <div class="form-group">
                                                    <label for="companyEmail">Email</label>
                                                    <input class="form-control" type="text" id="companyEmail"
                                                        name="companyEmail"
                                                        value="<?php echo htmlspecialchars($email); ?>"
                                                        placeholder="Enter Email">
                                                </div>

                                                <div class="form-group">
                                                    <label for="companyPlant">Plant</label>
                                                    <input class="form-control" type="text" id="companyPlant"
                                                        name="companyPlant"
                                                        value="<?php echo htmlspecialchars($plant); ?>"
                                                        placeholder="Enter Plant" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="companyPlantname">Plant Name</label>
                                                    <input class="form-control" type="text" id="companyPlantname"
                                                        name="companyPlantname"
                                                        value="<?php echo htmlspecialchars($plant_name); ?>"
                                                        placeholder="Enter Plant Name" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="companyAttention">Attention</label>
                                                    <input class="form-control" type="text" id="companyAttention"
                                                        name="companyAttention"
                                                        value="<?php echo htmlspecialchars($attention); ?>"
                                                        placeholder="Enter Attention Name" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary">Update Company
                                            Information</button>
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

    <?php include '../../includes/script.php'; ?>
</body>