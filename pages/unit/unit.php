<?php
include '../../includes/header.php';
include '../../includes/conn.php';
include '../../includes/session.php'; // Database connection
include '../../includes/check-permission.php';

// Fetch units
$unitQuery = "SELECT * FROM units";
$unitResult = $conn->query($unitQuery);

// Fetch conversion rules
$ruleQuery = "SELECT r.rule_id, u1.unit_name AS base_unit, u2.unit_name AS target_unit, r.conversion_factor
              FROM unit_conversions r
              JOIN units u1 ON r.base_unit_id = u1.unit_id
              JOIN units u2 ON r.target_unit_id = u2.unit_id";
$ruleResult = $conn->query($ruleQuery);
?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Unit Management</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-list-alt mr-1"></i> Existing Units
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Unit ID</th>
                                                <th>Unit Name</th>
                                                <th>Unit Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $unitResult->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php echo $row['unit_id']; ?></td>
                                                    <td><?php echo $row['unit_name']; ?></td>
                                                    <td><?php echo $row['unit_type']; ?></td>
                                                    <td>
                                                        <!-- Delete Unit Form -->
                                                        <form action="ctrl-unit/delete-unit.php" method="POST"
                                                            style="display:inline;">
                                                            <input type="hidden" name="unit_id"
                                                                value="<?php echo $row['unit_id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure you want to delete this unit?');">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-plus-circle mr-1"></i> Add New Unit
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="ctrl-unit/add-unit.php" method="POST">
                                        <div class="form-group">
                                            <label>Unit Name</label>
                                            <input type="text" class="form-control" id="unit_name" name="unit_name"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Unit Type</label>
                                            <select class="form-control" name="unit_type" required>
                                                <option value="">Select Unit Type</option>
                                                <?php
                                                include '../../../includes/conn.php';
                                                $query = "SHOW COLUMNS FROM units LIKE 'unit_type'";
                                                $result = $conn->query($query);
                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $enum_values = str_replace(["enum(", ")", "'"], "", $row["Type"]);
                                                    $unit_types = explode(",", $enum_values);
                                                    foreach ($unit_types as $type) {
                                                        echo "<option value='$type'>$type</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Unit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Super Admin Custom Computation Rules Section -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h3 class="card-title">
                                        <i class="fas fa-exchange-alt mr-1"></i> Custom Conversion Rules
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="ctrl-unit/add-conversion-rule.php" method="POST">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Base Unit</label>
                                                    <select name="base_unit_id" class="form-control" required>
                                                        <option value="">Select Base Unit</option>
                                                        <?php
                                                        $unitResult->data_seek(0); // Reset result pointer
                                                        while ($row = $unitResult->fetch_assoc()) { ?>
                                                            <option value="<?php echo $row['unit_id']; ?>">
                                                                <?php echo $row['unit_name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Target Unit</label>
                                                    <select name="target_unit_id" class="form-control" required>
                                                        <option value="">Select Target Unit</option>
                                                        <?php
                                                        $unitResult->data_seek(0); // Reset result pointer
                                                        while ($row = $unitResult->fetch_assoc()) { ?>
                                                            <option value="<?php echo $row['unit_id']; ?>">
                                                                <?php echo $row['unit_name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Conversion Factor</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        name="conversion_factor" required>
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="submit"
                                                        class="btn btn-success btn-block mt-2">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Display Existing Computation Rules -->
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h3 class="card-title">
                                        <i class="fas fa-check-circle mr-1"></i> Existing Conversion Rules
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Base Unit</th>
                                                <th>Target Unit</th>
                                                <th>Conversion Factor</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $ruleResult->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php echo $row['base_unit']; ?></td>
                                                    <td><?php echo $row['target_unit']; ?></td>
                                                    <td><?php echo $row['conversion_factor']; ?></td>
                                                    <td>
                                                        <form action="ctrl-unit/delete-rule.php" method="POST"
                                                            style="display:inline;">
                                                            <input type="hidden" name="rule_id"
                                                                value="<?php echo $row['rule_id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure you want to delete this rule?');">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Computation Rules -->
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h3 class="card-title">
                                        <i class="fas fa-calculator mr-1"></i> Define Computation Rules
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="ctrl-unit/add-computation-rule.php" method="POST">
                                        <div class="row">
                                            <!-- Base Unit -->
                                            <div class="col-md-3">
                                                <label>Base Unit</label>
                                                <select class="form-control" name="base_unit" required>
                                                    <?php $unitResult->data_seek(0);
                                                    while ($row = $unitResult->fetch_assoc()) {
                                                        echo "<option value='{$row['unit_id']}'>{$row['unit_name']}</option>";
                                                    } ?>
                                                </select>
                                            </div>

                                            <!-- Operation -->
                                            <div class="col-md-2">
                                                <label>Operation</label>
                                                <select class="form-control" name="operator" required>
                                                    <option value="+">Addition (+)</option>
                                                    <option value="-">Subtraction (-)</option>
                                                    <option value="*">Multiplication (×)</option>
                                                    <option value="/">Division (÷)</option>
                                                </select>
                                            </div>

                                            <!-- Target Unit -->
                                            <div class="col-md-3">
                                                <label>Target Unit</label>
                                                <select class="form-control" name="target_unit" required>
                                                    <?php $unitResult->data_seek(0);
                                                    while ($row = $unitResult->fetch_assoc()) {
                                                        echo "<option value='{$row['unit_id']}'>{$row['unit_name']}</option>";
                                                    } ?>
                                                </select>
                                            </div>

                                            <!-- Transaction Type -->
                                            <div class="col-md-2">
                                                <label>Applies To</label>
                                                <select class="form-control" name="transaction_type" required>
                                                    <option value="incoming">Incoming</option>
                                                    <option value="outgoing">Outgoing</option>
                                                </select>
                                            </div>

                                            <!-- Save Button -->
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-block">Save
                                                    Rule</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Computation Rules List -->
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h3 class="card-title">
                                        <i class="fas fa-clipboard-list mr-1"></i> Computation List
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Base Unit</th>
                                                <th>Operation</th>
                                                <th>Target Unit</th>
                                                <th>Transaction Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT cr.computation_rule_id, bu.unit_name AS base_unit, 
                                                    cr.operation, tu.unit_name AS target_unit, cr.transaction_type
                                            FROM unit_computations cr
                                            JOIN units bu ON cr.base_unit_id = bu.unit_id
                                            JOIN units tu ON cr.target_unit_id = tu.unit_id
                                            ORDER BY cr.computation_rule_id DESC";

                                            $result = $conn->query($query);
                                            if ($result->num_rows > 0) {
                                                $count = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                    <td>{$count}</td>
                                    <td>{$row['base_unit']}</td>
                                    <td>{$row['operation']}</td>
                                    <td>{$row['target_unit']}</td>
                                    <td>{$row['transaction_type']}</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm' onclick='deleteRule({$row['computation_rule_id']})'>Delete</button>
                                    </td>
                                  </tr>";
                                                    $count++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center'>No computation rules defined yet.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- JavaScript for Delete Confirmation -->
                        <script>
                            function deleteRule(id) {
                                if (confirm("Are you sure you want to delete this computation rule?")) {
                                    window.location.href = "ctrl-unit/delete-computation-rule.php?id=" + id;
                                }
                            }
                        </script>

                    </div>

                    <script>
                        function setOperator(op) {
                            document.getElementById("operator").value = op;
                        }
                    </script>


                </div>

        </div>
        <?php include '../../includes/footer.php'; ?>
    </div>
    </section>
    </div>
    </div>
    <?php include '../../includes/script.php'; ?>
</body>

</html>