<?php
include 'admin_header.php';
?>

<div class="container-fluid">
    <h1 class="mt-4">Category Wise Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Category Wise Sales Report</li>
    </ol>

    <!-- Sub-navigation for reports -->
    <div class="mb-4">
        <a href="reports.php" class="btn btn-outline-primary me-2">Sales Report</a>
        <a href="reports_date_wise.php" class="btn btn-outline-primary me-2">Date Wise</a>
        <a href="reports_day_wise.php" class="btn btn-outline-primary me-2">Day Wise</a>
        <a href="reports_category_wise.php" class="btn btn-primary me-2">Category Wise</a>
        <a href="reports_product_wise.php" class="btn btn-outline-primary me-2">Product Wise</a>
        <a href="reports_user_wise.php" class="btn btn-outline-primary me-2">User Wise</a>
        <a href="reports_promo_code_wise.php" class="btn btn-outline-primary">Promo Code Wise</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Sales Data by Category
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Total Orders</th>
                            <th>Total Sales</th>
                            <th>Average Order Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td>Prescription Spectacles</td>
                            <td>150</td>
                            <td>$22500.00</td>
                            <td>$150.00</td>
                        </tr>
                        <tr>
                            <td>Fashion Sunglasses</td>
                            <td>200</td>
                            <td>$20000.00</td>
                            <td>$100.00</td>
                        </tr>
                        <tr>
                            <td>Men's Watches</td>
                            <td>100</td>
                            <td>$30000.00</td>
                            <td>$300.00</td>
                        </tr>
                        <tr>
                            <td>Women's Watches</td>
                            <td>80</td>
                            <td>$24000.00</td>
                            <td>$300.00</td>
                        </tr>
                        <tr>
                            <td>Kids Sunglasses</td>
                            <td>120</td>
                            <td>$9600.00</td>
                            <td>$80.00</td>
                        </tr>
                        <tr>
                            <td>Sports Spectacles</td>
                            <td>70</td>
                            <td>$14000.00</td>
                            <td>$200.00</td>
                        </tr>
                        <tr>
                            <td>Smartwatches</td>
                            <td>50</td>
                            <td>$25000.00</td>
                            <td>$500.00</td>
                        </tr>
                        <tr>
                            <td>Blue Light Glasses</td>
                            <td>180</td>
                            <td>$10800.00</td>
                            <td>$60.00</td>
                        </tr>
                        <tr>
                            <td>Aviator Sunglasses</td>
                            <td>90</td>
                            <td>$13500.00</td>
                            <td>$150.00</td>
                        </tr>
                        <tr>
                            <td>Luxury Watches</td>
                            <td>30</td>
                            <td>$45000.00</td>
                            <td>$1500.00</td>
                        </tr>
                        <tr>
                            <td>Reading Glasses</td>
                            <td>250</td>
                            <td>$12500.00</td>
                            <td>$50.00</td>
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