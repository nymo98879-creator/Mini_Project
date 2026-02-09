<?php
require '../includes/middleware.php';
auth();
isUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .hero-section {
            background-color: #2563eb;
            color: white;
            padding: 80px 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        .hero-section h1 {
            font-size: 2.5rem;
        }

        .card {
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        footer {
            background-color: #111827;
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">ETEC CENTER</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container mt-4">
        <div class="hero-section">
            <h1>Welcome, <?= $_SESSION['username'] ?? 'User'; ?>!</h1>
            <p>Manage your orders, view products, and explore the dashboard</p>
            <a href="#" class="btn btn-light btn-lg mt-3">Get Started</a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <i class="fas fa-box fa-3x text-primary mb-3"></i>
                    <h5>Browse Products</h5>
                    <p>Check out our latest products and deals available for purchase.</p>
                    <a href="#" class="btn btn-primary btn-sm">View Products</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                    <h5>My Orders</h5>
                    <p>Track your orders, check status, and manage your purchases easily.</p>
                    <a href="#" class="btn btn-success btn-sm">View Orders</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <i class="fas fa-user fa-3x text-warning mb-3"></i>
                    <h5>Profile</h5>
                    <p>Update your profile, change password, and manage your account information.</p>
                    <a href="#" class="btn btn-warning btn-sm">View Profile</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5">
        <div class="container">
            <p>&copy; <?= date('Y'); ?> ETEC CENTER. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>