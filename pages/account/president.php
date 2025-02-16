<?php
include '../../includes/header.php';
include '../../includes/session.php'
    ?>

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
                            <h1>Create Account</h1>
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
                                <div class="card-header">
                                    <h5 class="card-title">Add President Account</h5>
                                </div>
                                <form action="ctrl-account/add-president.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstName">First Name</label>
                                                    <input class="form-control" type="text" id="firstName"
                                                        name="firstName" placeholder="Enter First Name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lastName">Last Name</label>
                                                    <input class="form-control" type="text" id="lastName"
                                                        name="lastName" placeholder="Enter Last Name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input class="form-control" type="text" id="email" name="email"
                                                        placeholder="Enter Email" required>
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input class="form-control" type="text" id="username"
                                                        name="username" placeholder="Enter Username" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input class="form-control" type="password" id="password"
                                                        name="password" placeholder="Enter Password" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirmPassword">Confirm Password</label>
                                                    <input class="form-control" type="password" id="confirmPassword"
                                                        name="confirmPassword" placeholder="Confirm Password" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary">Sign Up</button>
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