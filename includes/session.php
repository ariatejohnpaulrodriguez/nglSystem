<?php
include 'conn.php';
session_start();

if (!empty($_SESSION['employee_id'])) {
    $employee_id = $_SESSION['employee_id'];

    // Fetch employee details and role
    $stmt = $conn->prepare("SELECT e.*, r.role_name 
                            FROM employees e
                            JOIN roles r ON e.role_id = r.role_id
                            WHERE e.employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row_user = $result->fetch_array();

        $_SESSION['fullname'] = $row_user['first_name'] . " " . $row_user['last_name'];
        $_SESSION['email'] = $row_user['email'];
        $_SESSION['role'] = $row_user['role_name'];
        $_SESSION['role_id'] = $row_user['role_id'];

        // Define permission mapping manually (URL & icon based on permission name)
        $permissions_data = [
            'Create Roles' => [
                'name' => 'Create Roles', // Add 'name' key
                'url' => '#',
                'icon' => 'fas fa-user-plus',
                'sub_menu' => [
                    [
                        'name' => 'Add Roles',
                        'url' => '../../pages/account/role.php',
                        'icon' => 'fas fa-plus nav-icon'
                    ]
                ]
            ],
            'Roles' => [
                'name' => 'Roles',
                'url' => '#',
                'icon' => 'fas fa-list',
                'sub_menu' => [
                    [
                        'name' => 'Roles List',
                        'url' => '../../pages/account/role-list.php',
                        'icon' => 'fas fa-user-tag nav-icon'
                    ]
                ]
            ],
            'Create Gender' => [
                'name' => 'Create Gender',
                'url' => '#',
                'icon' => 'fas fa-venus-mars',
                'sub_menu' => [
                    [
                        'name' => 'Add Gender',
                        'url' => '../../pages/account/gender.php',
                        'icon' => 'fas fa-plus nav-icon'
                    ]
                ]
            ],
            'Gender' => [
                'name' => 'Gender',
                'url' => '#',
                'icon' => 'fas fa-list',
                'sub_menu' => [
                    [
                        'name' => 'Gender List',
                        'url' => '../../pages/account/gender-list.php',
                        'icon' => 'fas fa-user-tag nav-icon'
                    ]
                ]
            ],
            'Create Status' => [
                'name' => 'Create Status',
                'url' => '#',
                'icon' => 'fas fa-info-circle',
                'sub_menu' => [
                    [
                        'name' => 'Add Status',
                        'url' => '../../pages/account/status.php',
                        'icon' => 'fas fa-plus nav-icon'
                    ]
                ]
            ],
            'Create Unit Type' => [
                'name' => 'Manage Unit Type',
                'url' => '#',
                'icon' => 'fas fa-cube',
                'sub_menu' => [
                    [
                        'name' => 'Add Unit',
                        'url' => '../../pages/unit/unit.php',
                        'icon' => 'fas fa-plus nav-icon'
                    ]
                ]
            ],
            'Status' => [
                'name' => 'Status',
                'url' => '#',
                'icon' => 'fas fa-list',
                'sub_menu' => [
                    [
                        'name' => 'Status List',
                        'url' => '../../pages/account/status-list.php',
                        'icon' => 'fas fa-user-tag nav-icon'
                    ]
                ]
            ],
            'Create Employee' => [
                'name' => 'Create Employee',
                'url' => '#',
                'icon' => 'fas fa-user-plus',
                'sub_menu' => [
                    [
                        'name' => 'Add Employee',
                        'url' => '../../pages/account/employee.php',
                        'icon' => 'fas fa-plus nav-icon'
                    ]
                ]
            ],
            'Employee' => [
                'name' => 'Employee',
                'url' => '#',
                'icon' => 'fas fa-list',
                'sub_menu' => [
                    [
                        'name' => 'Employee',
                        'url' => '../../pages/account/employee-list.php',
                        'icon' => 'fas fa-database nav-icon'
                    ]
                ]
            ],
            'Register Product' => [
                'name' => 'Register Product',
                'url' => '#',
                'icon' => 'fas fa-edit',
                'sub_menu' => [
                    [
                        'name' => 'Add Product',
                        'url' => '../../pages/forms/add-product-form.php',
                        'icon' => 'fas fa-plus nav-icon'
                    ]
                ]
            ],
            'Product' => [
                'name' => 'Product',
                'url' => '#',
                'icon' => 'fas fa-list',
                'sub_menu' => [
                    [
                        'name' => 'Product List',
                        'url' => '../../pages/data/product-list.php',
                        'icon' => 'fas fa-clipboard-list nav-icon'
                    ]
                ]
            ],
            'Register Company' => [
                'name' => 'Register Company',
                'url' => '#',
                'icon' => 'fas fa-building',
                'sub_menu' => [
                    [
                        'name' => 'Company Form',
                        'url' => '../../pages/company/company.php',
                        'icon' => 'fas fa-edit nav-icon'
                    ]
                ]
            ],
            'Company' => [
                'name' => 'Company',
                'url' => '#',
                'icon' => 'fas fa-list',
                'sub_menu' => [
                    [
                        'name' => 'Company List',
                        'url' => '../../pages/company/company-list.php',
                        'icon' => 'fas fa-list nav-icon'
                    ]
                ]
            ],
            'Register Permission' => [
                'name' => 'Create Permissions',
                'url' => '#',
                'icon' => 'fas fa-lock',
                'sub_menu' => [
                    [
                        'name' => 'Add Role Permission',
                        'url' => '../../pages/permission/add-role-permission.php',
                        'icon' => 'fas fa-plus nav-icon'
                    ]
                ]
            ],
            'Manage Permission' => [
                'name' => 'Permissions',
                'url' => '#',
                'icon' => 'fas fa-user-shield',
                'sub_menu' => [
                    [
                        'name' => 'Permission List',
                        'url' => '../../pages/permission/permission-list.php',
                        'icon' => 'fas fa-list nav-icon'
                    ]
                ]
            ],
            'Transaction Form' => [
                'name' => 'Transaction Form',
                'url' => '#',
                'icon' => 'fas fa-file-invoice',
                'sub_menu' => [
                    [
                        'name' => 'Re Stock Form',
                        'url' => '../../pages/invoice/inv-request-form.php',
                        'icon' => 'fas fa-truck nav-icon'
                    ],
                    [
                        'name' => 'Delivery Receipt',
                        'url' => '../../pages/transfer/transfer-request-form.php',
                        'icon' => 'fas fa-truck nav-icon" style="transform: scaleX(-1);'
                    ]
                ]
            ],
            'Transaction History' => [
                'name' => 'Transaction History',
                'url' => '#',
                'icon' => 'fas fa-history',
                'sub_menu' => [
                    [
                        'name' => 'Recieved  Deliveries',
                        'url' => '../../pages/transaction/received-deliveries.php',
                        'icon' => 'fas fa-truck nav-icon'
                    ],
                    [
                        'name' => 'Sent Deliveries',
                        'url' => '../../pages/transaction/transfer-deliveries.php',
                        'icon' => 'fas fa-truck nav-icon" style="transform: scaleX(-1);'
                    ]
                ]
            ]
            ,
            'Delivery Blank Form' => [
                'name' => 'Blank Delivery Form',
                'url' => '#',
                'icon' => 'fas fa-file-invoice',
                'sub_menu' => [
                    [
                        'name' => 'Generate Delivery Form',
                        'url' => '../../pages/deliveryBlankForm/deliveryBlankForm.php',
                        'icon' => 'fas fa-edit nav-icon'
                    ]
                ]
            ]
        ];

        // Fetch allowed sidebar permissions for this role
        $permissions = [];
        $role_id = $_SESSION['role_id'];

        $perm_stmt = $conn->prepare("SELECT p.permission_name 
                                     FROM rolePermissions rp
                                     JOIN permissions p ON rp.permission_id = p.permission_id
                                     WHERE rp.role_id = ?");
        $perm_stmt->bind_param("i", $role_id);
        $perm_stmt->execute();
        $perm_result = $perm_stmt->get_result();

        while ($perm_row = $perm_result->fetch_assoc()) {
            $perm_name = $perm_row['permission_name'];

            // Map database permission name to URL & icon using $permissions_data
            if (isset($permissions_data[$perm_name])) {
                $permissions[] = $permissions_data[$perm_name];
            } else {
                // Log missing permissions for debugging
                error_log("Missing permission mapping for: " . $perm_name);
            }
        }

        $_SESSION['permissions'] = $permissions; // Store allowed menu items in session

        // Debugging - Print session data
        // echo '<pre>'; print_r($_SESSION['permissions']); echo '</pre>';
    } else {
        session_destroy();
        header("location: ../../pages/login/login.php");
        exit();
    }
} else {
    header("location: ../../pages/login/login.php");
    exit();
}
?>