<?php
include '../../includes/header.php'; // Assumes AdminLTE CSS/JS are included here
include '../../includes/session.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper d-flex align-items-center justify-content-center" style="min-height: 100vh;">

            <!-- Main content -->
            <section class="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-10"> <!-- Adjusted width -->
                            <div class="card card-danger shadow" style="width: 100%; max-width: 900px; margin: auto;">
                                <div class="card-header text-center">
                                    <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Access Denied
                                    </h3>
                                </div>
                                <div class="card-body text-center">
                                    <p class="lead">You do not have permission to view this page.</p>
                                    <a href="../dashboard/index.php" class="btn btn-primary">
                                        <i class="fas fa-home"></i> Return to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include '../../includes/footer.php'; ?>
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>