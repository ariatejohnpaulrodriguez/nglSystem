<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links (Burger Icon) -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <?php
        // Define the status to check for (e.g., "Pending")
        $attentionStatus = "Pending";

        // Fetch pending transfers
        $sqlTransfers = "SELECT transfer_id FROM transfers t INNER JOIN statuses s ON t.status_id = s.status_id WHERE s.status_name = ? LIMIT 5";
        $stmtTransfers = $conn->prepare($sqlTransfers);
        $transferNotifications = [];

        if ($stmtTransfers) {
            $stmtTransfers->bind_param("s", $attentionStatus);
            $stmtTransfers->execute();
            $resultTransfers = $stmtTransfers->get_result();

            if ($resultTransfers) {
                while ($row = $resultTransfers->fetch_assoc()) {
                    $transferNotifications[] = [
                        'id' => htmlspecialchars($row['transfer_id']),
                        'type' => 'transfer'
                    ];
                }
            } else {
                error_log("Error fetching transfer notifications: " . $conn->error);
            }
            $stmtTransfers->close();
        } else {
            error_log("Error preparing transfer notifications statement: " . $conn->error);
        }

        $notificationCount = count($transferNotifications);
        ?>
        <li class="nav-item dropdown d-flex align-items-center">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                <i class="fas fa-bell" style="color: black; font-size: 20px;"></i>
                <?php if ($notificationCount > 0): ?>
                <span class="badge badge-danger navbar-badge"><?php echo htmlspecialchars($notificationCount); ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?php echo htmlspecialchars($notificationCount); ?>
                    Transfer Notifications</span>
                <div class="dropdown-divider"></div>
                <?php
                // Display notifications (maximum of 15)
                $displayedCount = 0;
                foreach ($transferNotifications as $notification) {
                    if ($displayedCount >= 15)
                        break;

                    $icon = 'fas fa-truck nav-icon" style="transform: scaleX(-1);';
                    $url = '../../pages/transaction/transfer-deliveries.php';
                    $text = 'Transfer Delivery';
                    echo "<a href=\"{$url}\" class=\"dropdown-item\">
                            <i class=\"{$icon} mr-2\"></i> {$text} - {$notification['id']}
                            </a><div class=\"dropdown-divider\"></div>";
                    $displayedCount++;
                }
                ?>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown d-flex align-items-center">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                <img src="../../dist/img/boyIcon.jpg" class="img-circle" alt="User Image"
                    style="width: 20px; height: 20px;">
                <i class="fas fa-caret-down ml-1" style="font-size: 24px;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="../../pages/account/update-employee.php" class="dropdown-item">
                    <i class="fas fa-cog mr-2"></i> Edit Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="../../pages/login/userData/userLogout.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->