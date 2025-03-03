<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../pages/dashboard/index.php" class="brand-link">
        <img src="../../dist/img/nglLogo.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">NGL</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block"
                    style="font-size: 1.25rem;"><?php echo $_SESSION['fullname'] ?? 'Guest'; ?></a>
                <span class="d-block text-muted"
                    style="font-style: italic;"><?php echo $_SESSION['role'] ?? 'Unknown Role'; ?></span>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                if (isset($_SESSION['role']) && $_SESSION['role'] == "Master Key") {
                    echo '
                    <li class="nav-item">
                    <a href="../../pages/account/employee.php" class="nav-link">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>
                            Create Account
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/employee.php" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Employee</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="pages/account/president-list.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Account List
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/employee-list.php" class="nav-link">
                                <i class="fas fa-database nav-icon"></i>
                                <p>Employee </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Inventory
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/inventory/inventory-form.php" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Inventory</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-edit nav-icon"></i>
                        <p>
                            Manage Stock
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/inventory/inventory-list.php" class="nav-link">
                                 <i class="fas fa-list nav-icon"></i>
                                <p>Inventory</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">Event</li>
                <li class="nav-item">
                    <a href="pages/calendar.php" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                ';
                } elseif (isset($_SESSION['role']) && $_SESSION['role'] == "President") {
                    echo '<li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>
                            Create Roles
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/role.php" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Roles</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Roles
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/role-list.php" class="nav-link">
                                <i class="fas fa-user-tag nav-icon"></i>
                                <p>Roles List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-venus-mars"></i>
                        <p>
                            Create Gender
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/gender.php" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Gender</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Gender
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/gender-list.php" class="nav-link">
                                <i class="fas fa-user-tag nav-icon"></i>
                                <p>Gender List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-info-circle"></i>
                        <p>
                            Create Status
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/status.php" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Status</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Status
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/status-list.php" class="nav-link">
                                <i class="fas fa-user-tag nav-icon"></i>
                                <p>Status List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                    <li class="nav-item">
                    <a href="../../pages/account/employee.php" class="nav-link">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>
                            Create Employee
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/employee.php" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Employee</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="pages/account/employee-list.php" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Employee
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/account/employee-list.php" class="nav-link">
                                <i class="fas fa-database nav-icon"></i>
                                <p>Employee List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Register Product
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/forms/add-product-form.php" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Add Product</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Product
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/data/product-list.php" class="nav-link">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>Product List</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/inventory/inventory-list.php" class="nav-link">
                                <i class="fas fa-warehouse nav-icon"></i>
                                <p>Inventory</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>
                            Transaction Status
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/transaction/received-deliveries.php" class="nav-link">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Recieved  Deliveries</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/transaction/transfer-deliveries.php" class="nav-link">
                                <i class="fas fa-truck nav-icon" style="transform: scaleX(-1);"></i>
                                <p>Sent Deliveries</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Register Company
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/company/company.php" class="nav-link">
                                <i class="fas fa-edit nav-icon"></i>
                                <p>Company Form</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Company
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/company/company-list.php" class="nav-link">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Company List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">Event</li>
                <li class="nav-item">
                    <a href="pages/calendar.php" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                ';
                } elseif (isset($_SESSION['role']) && $_SESSION['role'] == "Warehouse Man") {
                    echo '
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Transaction Form
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/invoice/inv-request-form.php" class="nav-link">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Re Stock Form</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/transfer/transfer-request-form.php" class="nav-link">
                                <i class="fas fa-truck nav-icon" style="transform: scaleX(-1);"></i>
                                <p>Delivery Receipt</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>
                            Transaction History
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/transaction/received-deliveries.php" class="nav-link">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Recieved  Deliveries</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/transaction/transfer-deliveries.php" class="nav-link">
                                <i class="fas fa-truck nav-icon" style="transform: scaleX(-1);"></i>
                                <p>Sent Deliveries</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Product List
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../../pages/data/product-list.php" class="nav-link">
                                <i class="fas fa-list nav-icon"></i>
                                <p>View Product</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">Event</li>
                <li class="nav-item">
                    <a href="pages/calendar.php" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                ';
                }
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>