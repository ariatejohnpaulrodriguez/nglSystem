<?php
include '../../includes/conn.php';
include '../../includes/session.php';
include '../../includes/header.php';
?>

<?php
// Check if the user is logged in and has a specific role
//if (!isset($_SESSION['role'])) {
//header("Location: ../login/login.php"); // Redirect to login page
//exit();
//}

// You can further check if the role is allowed for this page
//$allowedRoles = ['President', 'Vice President', 'Secretary']; // Add more roles as needed

//if (!in_array($_SESSION['role'], $allowedRoles)) {
// If the user's role is not allowed, redirect to an unauthorized page or show an error
//header("Location: ../../pages/login/login.php");
//exit();
//}

// If user is logged in and has the allowed role, proceed with the rest of the page
?>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include '../../includes/sidebar.php'; ?>
    <?php include '../../includes/navbar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <a href="your_target_page.php" style="text-decoration: none; color: inherit;">
                    <h3>1</h3>
                    <p>New Incoming Delivery</p>
                  </a>
                </div>
                <div class="icon">
                  <i class="fas fa-truck"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div> <!-- /.content-wrapper -->

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/script.php'; ?>
  </div> <!-- /.wrapper -->
</body>

</html>