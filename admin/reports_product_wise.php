<?php
include 'admin_header.php';
?>

<div class="container-fluid">
    <h1 class="mt-4">Product Wise Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Product Wise Sales Report</li>
    </ol>

    <!-- Sub-navigation for reports -->
    <div class="mb-4">
        <a href="reports.php" class="btn btn-outline-primary me-2">Sales Report</a>
        <a href="reports_date_wise.php" class="btn btn-outline-primary me-2">Date Wise</a>
        <a href="reports_day_wise.php" class="btn btn-outline-primary me-2">Day Wise</a>
        <a href="reports_category_wise.php" class="btn btn-outline-primary me-2">Category Wise</a>
        <a href="reports_product_wise.php" class="btn btn-primary me-2">Product Wise</a>
        <a href="reports_user_wise.php" class="btn btn-outline-primary">User Wise</a>
        <a href="reports_promo_code_wise.php" class="btn btn-outline-primary">Promo Code Wise</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Sales Data by Product
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Total Units Sold</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Aviator Sunglasses</td>
                            <td>Sunglasses</td>
                            <td>250</td>
                            <td>$25000.00</td>
                        </tr>
                        <tr>
                            <td>Classic Watch</td>
                            <td>Watches</td>
                            <td>180</td>
                            <td>$36000.00</td>
                        </tr>
                        <tr>
                            <td>Reading Spectacles</td>
                            <td>Spectacles</td>
                            <td>300</td>
                            <td>$15000.00</td>
                        </tr>
                        <tr>
                            <td>Sports Sunglasses</td>
                            <td>Sunglasses</td>
                            <td>120</td>
                            <td>$18000.00</td>
                        </tr>
                        <tr>
                            <td>Smartwatch Pro</td>
                            <td>Watches</td>
                            <td>90</td>
                            <td>$45000.00</td>
                        </tr>
                        <tr>
                            <td>Blue Light Blocking Glasses</td>
                            <td>Spectacles</td>
                            <td>200</td>
                            <td>$12000.00</td>
                        </tr>
                        <tr>
                            <td>Designer Sunglasses</td>
                            <td>Sunglasses</td>
                            <td>100</td>
                            <td>$30000.00</td>
                        </tr>
                        <tr>
                            <td>Diving Watch</td>
                            <td>Watches</td>
                            <td>50</td>
                            <td>$20000.00</td>
                        </tr>
                        <tr>
                            <td>Kids Spectacles</td>
                            <td>Spectacles</td>
                            <td>150</td>
                            <td>$7500.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include 'admin_footer.php';
?>