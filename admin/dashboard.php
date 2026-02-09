<?php
require_once '../includes/middleware.php';
auth();
isAdmin();
require '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-Commerce</title>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            flex-shrink: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .sidebar .nav {
            padding: 20px 15px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
        }

        .sidebar .nav-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 260px);
        }

        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-navbar h4 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .logout-btn {
            padding: 8px 20px;
            background: #ef4444;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .content {
            padding: 30px;
            flex-grow: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                margin-left: -260px;
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .sidebar.active {
                margin-left: 0;
                width: 260px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>
                <span class="logo-icon">
                    <i class="fas fa-store"></i>
                </span>
                E-Commerce
            </h3>
        </div>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="#" class="nav-link active menu-link" data-page="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="products">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="categories">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="orders">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="users">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="reports">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="settings">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h4 id="page-title">Dashboard</h4>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span style="font-weight: 500;">Admin</span>
                <a href="../client/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>

        <!-- Dynamic Content Area -->
        <div class="content">
            <div id="page-content"></div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Load dashboard by default
            loadDashboard();

            // Menu click handler
            $('.menu-link').on('click', function (e) {
                e.preventDefault();

                $('.menu-link').removeClass('active');
                $(this).addClass('active');

                let page = $(this).data('page');

                // Update page title
                let pageTitle = $(this).find('span').text();
                $('#page-title').text(pageTitle);

                // Load appropriate page
                if (page === 'dashboard') loadDashboard();
                if (page === 'products') loadProducts();
                if (page === 'categories') loadCategories();
                if (page === 'orders') loadOrders();
                if (page === 'users') loadUsers();
                if (page === 'reports') loadReports();
                if (page === 'settings') loadSettings();
            });

            // Page loading functions
            function loadDashboard() {
                $('#page-content').load('dashboard/dashboard_view.php');
            }

            function loadProducts() {
                $('#page-content').load('product/view_product.php');
            }

            function loadCategories() {
                $('#page-content').html('<div class="alert alert-info">Categories page - Coming soon!</div>');
            }

            function loadOrders() {
                $('#page-content').html('<div class="alert alert-info">Orders page - Coming soon!</div>');
            }

            function loadUsers() {
                $('#page-content').html('<div class="alert alert-info">Users page - Coming soon!</div>');
            }

            function loadReports() {
                $('#page-content').html('<div class="alert alert-info">Reports page - Coming soon!</div>');
            }

            function loadSettings() {
                $('#page-content').html('<div class="alert alert-info">Settings page - Coming soon!</div>');
            }
        });
    </script>
</body>

</html>