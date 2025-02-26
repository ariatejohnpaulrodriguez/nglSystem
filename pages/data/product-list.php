<?php
include '../../includes/conn.php';
include '../../includes/session.php';

// Check user's role
$userRole = $_SESSION['role'];
?>

<?php
include '../../includes/header.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <?php include '../../includes/navbar.php'; ?>
    <!-- /.navbar -->
    <?php include '../../includes/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Product List</h3>
                  <a href="export-csv-product.php" class="btn btn-xs btn-success" style="margin-left: 20px;">Export as
                    CSV</a><a href="export-pdf-product.php" class="btn btn-xs btn-danger"
                    style="margin-left: 10px;">Export as
                    PDF</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Product ID</th>
                        <th>Code</th>
                        <th>Brand</th>
                        <th>Description</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // SQL query to fetch data
                      $sql = "SELECT product_id, code, brand, description FROM products";
                      $result = mysqli_query($conn, $sql);

                      // Check if there are any rows returned
                      if (mysqli_num_rows($result) > 0) {
                        // Loop through the rows and display them in the table
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>";
                          echo "<td>" . $row["product_id"] . "</td>";
                          echo "<td>" . $row["code"] . "</td>";
                          echo "<td>" . $row["brand"] . "</td>";
                          echo "<td>" . $row["description"] . "</td>";
                          echo "<td>";

                          // Show Edit and Delete buttons only if the user is not a Warehouse Man
                          if ($userRole != "Warehouse Man") {
                            echo "<form action='update-product.php' method='post' style='display:inline-block;'>";
                            echo "<input type='hidden' name='product_id' value='" . $row["product_id"] . "'>";
                            echo "<input type='submit' value='Edit' class='btn btn-primary'>";
                            echo "</form>";
                            echo "<form action='ctrl-data/delete-product.php' method='post' style='display:inline-block;' onsubmit='return confirmDelete()'>";
                            echo "<input type='hidden' name='product_id' value='" . $row["product_id"] . "'>";
                            echo "<input type='submit' value='Delete' class='btn btn-danger'>";
                            echo "</form>";
                          } else {
                            echo "<form action='#' method='post' style='display:inline-block;'>";
                            echo "<input type='hidden' name='product_id' value='" . $row["product_id"] . "'>";
                            echo "<input type='submit' value='View' class='btn btn-primary'>";
                            echo "</form>";
                          }

                          echo "</td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr><td colspan='6'>No data found</td></tr>";
                      }

                      mysqli_close($conn);
                      ?>
                    </tbody>
                    <!-- JavaScript for Confirmation -->
                    <script>
                      function confirmDelete() {
                        return confirm("Are you sure you want to delete this product?");
                      }
                    </script>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include '../../includes/footer.php'; ?>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <?php include '../../includes/script.php'; ?>
</body>

</html>