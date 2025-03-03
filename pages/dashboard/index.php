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
                <div class="inner">
                  <h3><?php echo htmlspecialchars($totalInvoices); ?></h3>
                  <p>Re Stock</p>
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
                  <p>Delivery Receipt</p>
                </div>

                <div class="icon">
                  <i class="fas fa-truck nav-icon" style="transform: scaleX(-1);"></i>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="container-fluid">
          <div class="col-md-13">
            <div class="card">
              <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> <strong>Note:</strong> Stocks Status</h3>
              </div>

              <?php
              include '../../includes/conn.php'; // Database connection
              
              $query = "SELECT 
              p.product_id, 
              p.description, 
              p.brand, 
              p.code,
              s.current_quantity
              FROM products p
              JOIN stocks s ON p.product_id = s.product_id";

              $result = $conn->query($query);

              if (!$result) {
                die("Query failed: " . $conn->error);
              }
              ?>

              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Product ID</th>
                        <th>Description</th>
                        <th>Brand</th>
                        <th>Code</th>
                        <th>Availability</th>
                        <th>Stock</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                          // Ensure quantity exists in the row
                          $quantity = isset($row['current_quantity']) ? $row['current_quantity'] : 0;

                          // Determine progress bar and badge color based on quantity
                          if ($quantity <= 25) {
                            $colorClass = 'bg-danger'; // Red
                          } elseif ($quantity <= 75) {
                            $colorClass = 'bg-warning'; // Orange
                          } else {
                            $colorClass = 'bg-success'; // Green
                          }

                          // Calculate percentage for progress bar (ensuring it doesn't exceed 100%)
                          $progressPercentage = min($quantity, 100);

                          echo "<tr class='clickable-row' data-toggle='collapse' data-target='#details" . $row['product_id'] . "'>";
                          echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['code']) . "</td>";

                          // Progress Bar (AdminLTE 3.2.0 supports bg-danger, bg-warning, bg-success)
                          echo "<td>";
                          echo "<div class='progress progress-xs' style='height: 10px; margin-bottom: 0;'>";
                          echo "<div class='progress-bar " . $colorClass . "' style='width: " . $progressPercentage . "%;'></div>";
                          echo "</div>";
                          echo "</td>";

                          // Quantity Badge with the same background color
                          echo "<td><span class='badge " . $colorClass . "'>" . $quantity . "</span></td>";
                          echo "</tr>";

                          // Hidden Row for Product Details
                          echo "<tr id='details" . $row['product_id'] . "' class='collapse'>";
                          echo "<td colspan='12'>";
                          echo "<div class='card wrapper'>";
                          echo "<div class='card-header bg-dark d-flex align-items-center justify-content-between'>";
                          echo "<h3 class='card-title text-white'>Product Details</h3>";
                          echo "</div>";
                          echo "<div class='card-body'>";

                          // Two-column centered layout with card bodies
                          echo "<div class='row d-flex justify-content-center text-center'>";

                          // Column 1
                          echo "<div class='col-12 col-md-6 mb-3'>";
                          echo "<div class='card h-500'>";
                          echo "<div class='card-header bg-info text-white'>Re Stock Info</div>";
                          echo "<div class='card-body'>";
                          echo "<p>Content for the first column...</p>";
                          echo "</div>"; // End of card-body
                          echo "</div>"; // End of card
                          echo "</div>"; // End of col-md-5
                      
                          // Column 2
                          echo "<div class='col-12 col-md-6 mb-3'>";
                          echo "<div class='card h-500'>";
                          echo "<div class='card-header bg-purple text-white'>Delivery Info</div>";
                          echo "<div class='card-body'>";
                          echo "<p>Content for the second column...</p>";
                          echo "</div>"; // End of card-body
                          echo "</div>"; // End of card
                          echo "</div>"; // End of col-md-5
                      
                          echo "</div>"; // End of row
                      
                          echo "</div>"; // End of main card-body
                          echo "</div>"; // End of main card wrapper
                          echo "</td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr><td colspan='12'>No records found.</td></tr>";
                      }
                      ?>
                    </tbody>

                  </table>
                </div>
              </div>
              <!-- /.card -->

            </div>
            <!-- /.col -->
          </div>
        </div>
      </section>
    </div><!-- /.content-wrapper -->
    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/script.php'; ?>
  </div> <!-- /.wrapper -->
</body>

</html>