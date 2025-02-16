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
                            <h1>Update Gender</h1>
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
                                $gender_id = '';
                                $gender_name = '';

                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $gender_id = $_POST['gender_id'];

                                    // Fetch the president data based on the ID
                                    $query = "SELECT gender_name FROM genders WHERE gender_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $gender_id);
                                    $stmt->execute();
                                    $stmt->bind_result($gender_name);
                                    $stmt->fetch();
                                    $stmt->close();
                                    $conn->close();
                                }
                                ?>
                                <div class="card-header">
                                    <h5 class="card-title">Change Gender</h5>
                                </div>
                                <form action="ctrl-account/update-ctrl-gender.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <label for="gender_id">Gender ID</label>
                                                <input class="form-control" type="text" id="gender_id" name="gender_id"
                                                    value="<?php echo htmlspecialchars($gender_id); ?>" required
                                                    readonly>
                                                <br>

                                                <div class="form-group">
                                                    <label for="genderName">Edit Gender Name</label>
                                                    <input class="form-control" type="text" id="genderName"
                                                        name="genderName"
                                                        value="<?php echo htmlspecialchars($gender_name); ?>"
                                                        placeholder="Enter New Gender Name" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary">Update</button>
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