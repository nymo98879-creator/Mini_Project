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

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            flex-shrink: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .sidebar-header {
            padding: 30px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .sidebar-header h3 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-header .logo-icon {
            width: 45px;
            height: 45px;
            background: var(--primary-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .sidebar .nav {
            padding: 25px 15px;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 15px;
            margin: 20px 0 10px 0;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 15px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-gradient);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(8px);
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.2);
        }

        .sidebar .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 280px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 280px);
            transition: all 0.3s ease;
        }

        .top-navbar {
            background: white;
            padding: 20px 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .page-title-section h4 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-breadcrumb {
            font-size: 13px;
            color: #64748b;
            margin-top: 5px;
        }

        .page-breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-icon:hover {
            transform: scale(1.1);
            background: var(--primary-gradient);
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            background: #ef4444;
            border-radius: 50%;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #1e293b;
        }

        .user-role {
            font-size: 12px;
            color: #64748b;
        }

        .logout-btn {
            padding: 10px 24px;
            background: var(--danger-gradient);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
            border: none;
        }

        .logout-btn:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(235, 51, 73, 0.4);
        }

        .content {
            padding: 35px;
            flex-grow: 1;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            width: 40px;
            height: 40px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-toggle:hover {
            transform: scale(1.1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                margin-left: -280px;
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .user-details {
                display: none;
            }

            .page-title-section h4 {
                font-size: 22px;
            }
        }

        @media (max-width: 576px) {
            .content {
                padding: 20px;
            }

            .top-navbar {
                padding: 15px 20px;
            }

            .notification-icon {
                display: none;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(102, 126, 234, 0.2);
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>
                <span class="logo-icon">
                    <i class="fas fa-store"></i>
                </span>
                <span>E-Commerce</span>
            </h3>
        </div>
        <ul class="nav nav-pills flex-column">
            <div class="nav-section-title">Main Menu</div>
            <li class="nav-item">
                <a href="#" class="nav-link active menu-link" data-page="dashboard">
                    <i class="fas fa-chart-line"></i>
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
            
            <div class="nav-section-title">Management</div>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="users">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link menu-link" data-page="reports">
                    <i class="fas fa-file-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>
            
            <div class="nav-section-title">System</div>
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
            <button class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="page-title-section">
                <h4 id="page-title">Dashboard</h4>
                <div class="page-breadcrumb">
                    <a href="#"><i class="fas fa-home"></i> Home</a>
                    <span class="mx-2">/</span>
                    <span id="page-breadcrumb">Dashboard</span>
                </div>
            </div>
            
            <div class="user-info">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-details">
                        <div class="user-name">Admin User</div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                
                <a href="../client/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
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
            // Mobile menu toggle
            $('#mobileToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() <= 992) {
                    if (!$(e.target).closest('.sidebar, .mobile-toggle').length) {
                        $('#sidebar').removeClass('active');
                    }
                }
            });

            // Load dashboard by default
            loadDashboard();

            // Menu click handler
            $('.menu-link').on('click', function (e) {
                e.preventDefault();

                // Show loading
                showLoading();

                $('.menu-link').removeClass('active');
                $(this).addClass('active');

                let page = $(this).data('page');

                // Update page title and breadcrumb
                let pageTitle = $(this).find('span').text();
                $('#page-title').text(pageTitle);
                $('#page-breadcrumb').text(pageTitle);

                // Close mobile menu
                if ($(window).width() <= 992) {
                    $('#sidebar').removeClass('active');
                }

                // Load appropriate page with delay for smooth transition
                setTimeout(function() {
                    if (page === 'dashboard') loadDashboard();
                    if (page === 'products') loadProducts();
                    if (page === 'categories') loadCategories();
                    if (page === 'orders') loadOrders();
                    if (page === 'users') loadUsers();
                    if (page === 'reports') loadReports();
                    if (page === 'settings') loadSettings();
                }, 300);
            });

            // Page loading functions
            function loadDashboard() {
                $('#page-content').load('dashboard/dashboard_view.php', function() {
                    hideLoading();
                });
            }

            function loadProducts() {
                $('#page-content').load('product/view_product.php', function() {
                    hideLoading();
                });
            }

            function loadCategories() {
                $('#page-content').html(`
                    <div class="alert alert-info border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); border-left: 4px solid #4facfe !important;">
                        <i class="fas fa-info-circle me-2"></i>Categories page - Coming soon!
                    </div>
                `);
                hideLoading();
            }

            function loadOrders() {
                $('#page-content').html(`
                    <div class="alert alert-info border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); border-left: 4px solid #4facfe !important;">
                        <i class="fas fa-info-circle me-2"></i>Orders page - Coming soon!
                    </div>
                `);
                hideLoading();
            }

            function loadUsers() {
                $('#page-content').html(`
                    <div class="alert alert-info border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); border-left: 4px solid #4facfe !important;">
                        <i class="fas fa-info-circle me-2"></i>Users page - Coming soon!
                    </div>
                `);
                hideLoading();
            }

            function loadReports() {
                $('#page-content').html(`
                    <div class="alert alert-info border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); border-left: 4px solid #4facfe !important;">
                        <i class="fas fa-info-circle me-2"></i>Reports page - Coming soon!
                    </div>
                `);
                hideLoading();
            }

            function loadSettings() {
                $('#page-content').html(`
                    <div class="alert alert-info border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); border-left: 4px solid #4facfe !important;">
                        <i class="fas fa-info-circle me-2"></i>Settings page - Coming soon!
                    </div>
                `);
                hideLoading();
            }

            // Loading functions
            function showLoading() {
                $('#loadingOverlay').addClass('show');
            }

            function hideLoading() {
                setTimeout(function() {
                    $('#loadingOverlay').removeClass('show');
                }, 350);
            }
        });
    </script>
</body>

</html>