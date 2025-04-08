<?php
include '../../includes/conn.php';
include '../../includes/session.php';
include '../../includes/check-permission.php';
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
                        <th>Unit</th> <!-- Show unit_name instead of unit_id -->
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      include '../../includes/conn.php';

                      // Fetch products with unit names using LEFT JOIN
                      $sql = "SELECT p.product_id, p.code, p.brand, p.description, u.unit_name 
                    FROM products p
                    LEFT JOIN units u ON p.unit_id = u.unit_id"; // Join with units table
                      
                      $result = mysqli_query($conn, $sql);

                      if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>";
                          echo "<td>" . $row["product_id"] . "</td>";
                          echo "<td>" . $row["code"] . "</td>";
                          echo "<td>" . $row["brand"] . "</td>";
                          echo "<td>" . $row["description"] . "</td>";
                          echo "<td>" . htmlspecialchars($row["unit_name"] ?? 'N/A') . "</td>"; // Show unit name
                          echo "<td>";

                          // Edit button
                          echo "<form action='update-product.php' method='post' style='display:inline-block; margin-right:5px;'>";
                          echo "<input type='hidden' name='product_id' value='" . $row["product_id"] . "'>";
                          echo "<input type='submit' value='Edit' class='btn btn-primary mb-1'>";
                          echo "</form>";

                          // Delete button
                          echo "<form action='ctrl-data/delete-product.php' method='post' style='display:inline-block;' onsubmit='return confirmDelete()'>";
                          echo "<input type='hidden' name='product_id' value='" . $row["product_id"] . "'>";
                          echo "<button type='button' class='btn btn-danger' onclick='deleteProductModal(" . $row["product_id"] . ")'>Delete</button>";
                          echo "</form>";

                          echo "</td>";
                          echo "</tr>";
                        }
                      } else {
                        echo "<tr><td colspan='6' class='text-center'>No products found</td></tr>";
                      }

                      mysqli_close($conn);
                      ?>
                    </tbody>
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

      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="productDeleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-danger">
              <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Deletion</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <form id="deleteForm" action="ctrl-data/delete-product.php" method="post">
                <input type="hidden" name="product_id" id="product_id_to_delete">
                <button type="submit" class="btn btn-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- /.content-wrapper -->
    <?php include '../../includes/footer.php'; ?>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <?php include '../../includes/script.php'; ?>
</body>

</html>