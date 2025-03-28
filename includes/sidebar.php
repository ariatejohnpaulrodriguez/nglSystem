<?php
// Test Sidebar for Role-Based Access Control
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../../pages/dashboard/index.php" class="brand-link">
        <img src="../../dist/img/nglCircleLogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
        <span class=" brand-text font-weight-light">New Generation Link</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="../../pages/account/update-employee.php" class="d-block" style="font-size: 1.25rem;">
                    <?php echo $_SESSION['fullname'] ?? 'Guest'; ?>
                </a>
                <span class="d-block text-muted" style="font-style: italic;">
                    <?php echo $_SESSION['role'] ?? 'Unknown Role'; ?>
                </span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if (!empty($_SESSION['permissions'])): ?>
                    <?php foreach ($_SESSION['permissions'] as $perm): ?>
                        <li class="nav-item">
                            <a href="<?= $perm['url'] ?>" class="nav-link">
                                <i class="nav-icon <?= $perm['icon'] ?>"></i>
                                <p>
                                    <?= $perm['name'] ?>
                                    <?php if (!empty($perm['sub_menu'])): ?>
                                        <i class="fas fa-angle-left right"></i>
                                    <?php endif; ?>
                                </p>
                            </a>
                            <?php if (!empty($perm['sub_menu'])): ?>
                                <ul class="nav nav-treeview">
                                    <?php foreach ($perm['sub_menu'] as $sub): ?>
                                        <li class="nav-item">
                                            <a href="<?= $sub['url'] ?>" class="nav-link">
                                                <i class="<?= $sub['icon'] ?>"></i>
                                                <p><?= $sub['name'] ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </nav>
    </div>
</aside>