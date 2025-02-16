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
                            <h1>Update Account</h1>
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
                                $president_id = '';
                                $firstName = '';
                                $lastName = '';
                                $email = '';
                                $username = '';

                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $president_id = $_POST['president_id'];

                                    // Fetch the president data based on the ID
                                    $query = "SELECT president_firstname, president_lastname, president_email, president_username FROM tbl_president WHERE president_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $president_id);
                                    $stmt->execute();
                                    $stmt->bind_result($firstName, $lastName, $email, $username);
                                    $stmt->fetch();
                                    $stmt->close();
                                    $conn->close();
                                }
                                ?>
                                <div class="card-header">
                                    <h5 class="card-title">Change President Account</h5>
                                </div>
                                <form action="ctrl-account/update-ctrl-president.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <label for="president_id">President ID</label>
                                                <input class="form-control" type="text" id="president_id"
                                                    name="president_id"
                                                    value="<?php echo htmlspecialchars($president_id); ?>" required
                                                    readonly>
                                                <br>

                                                <div class="form-group">
                                                    <label for="firstName">First Name</label>
                                                    <input class="form-control" type="text" id="firstName"
                                                        name="firstName"
                                                        value="<?php echo htmlspecialchars($firstName); ?>"
                                                        placeholder="Enter First Name" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="lastName">Last Name</label>
                                                    <input class="form-control" type="text" id="lastName"
                                                        name="lastName"
                                                        value="<?php echo htmlspecialchars($lastName); ?>"
                                                        placeholder="Enter Last Name" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input class="form-control" type="text" id="email" name="email"
                                                        value="<?php echo htmlspecialchars($email); ?>"
                                                        placeholder="Enter Email" required>
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input class="form-control" type="text" id="username"
                                                        name="username"
                                                        value="<?php echo htmlspecialchars($username); ?>"
                                                        placeholder="Enter Username" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="password">New Password</label>
                                                    <input class="form-control" type="password" id="password"
                                                        name="password" placeholder="Enter Password">
                                                </div>

                                                <div class="form-group">
                                                    <label for="confirmPassword">Confirm New Password</label>
                                                    <input class="form-control" type="password" id="confirmPassword"
                                                        name="confirmPassword" placeholder="Confirm Password">
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