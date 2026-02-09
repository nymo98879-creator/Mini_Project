<?php
require_once '../../config/database.php';

// Get statistics
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$totalCategories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM categories"))['count'];
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];

// Calculate total revenue (if orders table exists)
$revenueQuery = "SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'";
$revenueResult = mysqli_query($conn, $revenueQuery);
$totalRevenue = 0;
if ($revenueResult) {
    $totalRevenue = mysqli_fetch_assoc($revenueResult)['total'] ?? 0;
}

// Get low stock products
$lowStockQuery = "SELECT * FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC LIMIT 5";
$lowStockProducts = mysqli_query($conn, $lowStockQuery);

// Get top selling products (if order_items table exists)
$topProductsQuery = "SELECT p.*, COALESCE(SUM(oi.quantity), 0) as total_sold 
                      FROM products p 
                      LEFT JOIN order_items oi ON p.product_id = oi.product_id 
                      GROUP BY p.product_id 
                      ORDER BY total_sold DESC 
                      LIMIT 5";
$topProductsResult = mysqli_query($conn, $topProductsQuery);
$topProducts = [];
if ($topProductsResult) {
    while ($row = mysqli_fetch_assoc($topProductsResult)) {
        $topProducts[] = $row;
    }
}

// Get products by category
$categoryStatsQuery = "SELECT c.name, COUNT(p.product_id) as count 
                       FROM categories c 
                       LEFT JOIN products p ON c.category_id = p.category_id 
                       GROUP BY c.category_id";
$categoryStats = mysqli_query($conn, $categoryStatsQuery);
?>

<style>
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card.blue {
        border-left-color: #3b82f6;
    }

    .stat-card.green {
        border-left-color: #10b981;
    }

    .stat-card.orange {
        border-left-color: #f59e0b;
    }

    .stat-card.purple {
        border-left-color: #8b5cf6;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin: 10px 0 5px 0;
    }

    .stat-label {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .chart-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    .chart-card h5 {
        margin-bottom: 20px;
        color: #1e293b;
        font-weight: 600;
    }

    .table-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-top: 20px;
    }

    .table-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 25px;
        font-weight: 600;
    }

    .badge-low {
        background: #fef3c7;
        color: #92400e;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-high {
        background: #d1fae5;
        color: #065f46;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .product-rank {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: white;
    }

    .rank-1 {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    }

    .rank-2 {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
    }

    .rank-3 {
        background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
    }

    .rank-other {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }
</style>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card blue">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Products</div>
                    <div class="stat-value"><?php echo $totalProducts; ?></div>
                    <small class="text-muted"><i class="fas fa-box me-1"></i> In Inventory</small>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card green">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Categories</div>
                    <div class="stat-value"><?php echo $totalCategories; ?></div>
                    <small class="text-muted"><i class="fas fa-tags me-1"></i> Active</small>
                </div>
                <div class="stat-icon green">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card orange">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value"><?php echo $totalUsers; ?></div>
                    <small class="text-muted"><i class="fas fa-user-check me-1"></i> Registered</small>
                </div>
                <div class="stat-icon orange">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card purple">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">$<?php echo number_format($totalRevenue, 2); ?></div>
                    <small class="text-muted"><i class="fas fa-chart-line me-1"></i> All Time</small>
                </div>
                <div class="stat-icon purple">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4">
    <div class="col-xl-8">
        <div class="chart-card">
            <h5><i class="fas fa-chart-bar me-2"></i>Products by Category</h5>
            <canvas id="categoryBarChart" height="80"></canvas>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="chart-card">
            <h5><i class="fas fa-chart-pie me-2"></i>Category Distribution</h5>
            <canvas id="categoryPieChart"></canvas>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-4 mt-2">
    <div class="col-xl-6">
        <div class="table-card">
            <div class="table-card-header">
                <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($lowStockProducts) > 0):
                            while ($product = mysqli_fetch_assoc($lowStockProducts)):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><strong><?php echo $product['stock_quantity']; ?></strong></td>
                                    <td><span class="badge-low">Low Stock</span></td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    All products have sufficient stock
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="table-card">
            <div class="table-card-header">
                <i class="fas fa-fire me-2"></i>Top Selling Products
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">Rank</th>
                            <th>Product</th>
                            <th>Sold</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($topProducts)):
                            $rank = 1;
                            foreach ($topProducts as $product):
                                $rankClass = $rank <= 3 ? "rank-$rank" : "rank-other";
                                ?>
                                <tr>
                                    <td>
                                        <span class="product-rank <?php echo $rankClass; ?>">
                                            <?php echo $rank; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><strong><?php echo $product['total_sold']; ?></strong></td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                </tr>
                                <?php
                                $rank++;
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    No sales data available yet
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
(function() {
    // 1. Data Preparation (PHP to JS)
    <?php
        $categoryNames = [];
        $categoryCounts = [];
        if (isset($categoryStats) && mysqli_num_rows($categoryStats) > 0) {
            mysqli_data_seek($categoryStats, 0);
            while ($cat = mysqli_fetch_assoc($categoryStats)) {
                $categoryNames[] = $cat['name'];
                $categoryCounts[] = $cat['count'];
            }
        }
        ?>

        const categoryLabels = <?php echo json_encode($categoryNames); ?>;
        const categoryData = <?php echo json_encode($categoryCounts); ?>;

        function renderDashboardCharts() {
            // ERROR CHECK: Ensure Chart.js library exists
            if (typeof Chart === 'undefined') {
                console.error("Chart.js not found. Please check your <script> tags.");
                return;
            }

            const barCanvas = document.getElementById('categoryBarChart');
            const pieCanvas = document.getElementById('categoryPieChart');

            // Only run if the canvas elements actually exist in the DOM
            if (!barCanvas || !pieCanvas) return;

            // Cleanup: Destroy existing instances to prevent "Canvas already in use" errors
            if (window.myBarChart instanceof Chart) window.myBarChart.destroy();
            if (window.myPieChart instanceof Chart) window.myPieChart.destroy();

            // Initialize Bar Chart
            window.myBarChart = new Chart(barCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Number of Products',
                        data: categoryData,
                        backgroundColor: 'rgba(102, 126, 234, 0.8)',
                        borderColor: '#667eea',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });

            // Initialize Pie Chart
            window.myPieChart = new Chart(pieCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#ec4899'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 15, font: { size: 12 } } }
                    }
                }
            });
        }

        // TRIGGER MECHANISM:
        // We use a small delay to ensure the DOM is fully painted, 
        // especially important when using window.location.href or AJAX
        setTimeout(renderDashboardCharts, 0);

        // Ensure it works on Back/Forward button clicks
        window.addEventListener('pageshow', function () {
            setTimeout(renderDashboardCharts, 100);
        });
    })();
</script>