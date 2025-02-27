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



// Define the status you want to check for (e.g., "Pending", or an empty status)
$attentionStatus = "No Status"; // Or an empty string "" if you want to check for null/empty statuses

// Fetch the total number of invoices
$sqlInvoiceCount = "SELECT COUNT(invoice_id) AS total_invoices FROM invoices";
$resultInvoiceCount = $conn->query($sqlInvoiceCount);

if ($resultInvoiceCount) {
  $rowInvoiceCount = $resultInvoiceCount->fetch_assoc();
  $totalInvoices = $rowInvoiceCount['total_invoices'];
} else {
  $totalInvoices = "Error"; // Or some default value
  error_log("Error fetching invoice count: " . $conn->error);
}

// Fetch the total number of transfers
$sqlTransferCount = "SELECT COUNT(transfer_id) AS total_transfers FROM transfers";
$resultTransferCount = $conn->query($sqlTransferCount);

if ($resultTransferCount) {
  $rowTransferCount = $resultTransferCount->fetch_assoc();
  $totalTransfers = $rowTransferCount['total_transfers'];
} else {
  $totalTransfers = "Error"; // Or some default value
  error_log("Error fetching transfer count: " . $conn->error);
}

// Fetch the count of invoices with the specified status
$sqlAttentionCount = "SELECT COUNT(invoice_id) AS attention_count 
                        FROM invoices i
                        INNER JOIN statuses s ON i.status_id = s.status_id
                        WHERE s.status_name = ?";
$stmtAttentionCount = $conn->prepare($sqlAttentionCount);

if ($stmtAttentionCount) {
  $stmtAttentionCount->bind_param("s", $attentionStatus);
  $stmtAttentionCount->execute();
  $resultAttentionCount = $stmtAttentionCount->get_result();

  if ($resultAttentionCount) {
    $rowAttentionCount = $resultAttentionCount->fetch_assoc();
    $attentionCount = $rowAttentionCount['attention_count'];
  } else {
    $attentionCount = 0; // Or some default value
    error_log("Error fetching attention invoice count: " . $conn->error);
  }
  $stmtAttentionCount->close();
} else {
  $attentionCount = 0;
  error_log("Error preparing attention invoice count statement: " . $conn->error);
}

// Fetch the count of transfers with the specified status
$sqlTransferAttentionCount = "SELECT COUNT(t.transfer_id) AS transfer_attention_count
                                FROM transfers t
                                INNER JOIN statuses s ON t.status_id = s.status_id
                                WHERE s.status_name = ?";
$stmtTransferAttentionCount = $conn->prepare($sqlTransferAttentionCount);

if ($stmtTransferAttentionCount) {
  $stmtTransferAttentionCount->bind_param("s", $attentionStatus);
  $stmtTransferAttentionCount->execute();
  $resultTransferAttentionCount = $stmtTransferAttentionCount->get_result();

  if ($resultTransferAttentionCount) {
    $rowTransferAttentionCount = $resultTransferAttentionCount->fetch_assoc();
    $transferAttentionCount = $rowTransferAttentionCount['transfer_attention_count'];
  } else {
    $transferAttentionCount = 0; // Or some default value
    error_log("Error fetching attention transfer count: " . $conn->error);
  }
  $stmtTransferAttentionCount->close();
} else {
  $transferAttentionCount = 0;
  error_log("Error preparing attention transfer count statement: " . $conn->error);
}

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
              <div class="small-box bg-info" style="cursor: pointer;"
                onclick="window.location.href='../../pages/transaction/received-deliveries.php'">
                <?php if ($attentionCount > 0): ?>
                  <span class="badge badge-danger"
                    style="position: absolute; top: -5px; right: -5px;"><?php echo htmlspecialchars($attentionCount); ?></span>
                <?php endif; ?>
                <div class="inner">
                  <h3><?php echo htmlspecialchars($totalInvoices); ?></h3>
                  <p>Incoming Delivery</p>
                </div>
                <div class="icon">
                  <i class="fas fa-truck"></i>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <div class="small-box bg-purple" style="cursor: pointer;"
                onclick="window.location.href='../../pages/transaction/transfer-deliveries.php'">

                <?php if ($transferAttentionCount > 0): ?>
                  <span class="badge badge-danger"
                    style="position: absolute; top: -5px; right: -5px;"><?php echo htmlspecialchars($transferAttentionCount); ?></span>
                <?php endif; ?>

                <div class="inner">
                  <h3><?php echo htmlspecialchars($totalTransfers); ?></h3>
                  <p>Transfer Delivery</p>
                </div>

                <div class="icon">
                  <i class="fas fa-truck nav-icon" style="transform: scaleX(-1);"></i>
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