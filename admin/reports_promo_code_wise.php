<?php
include 'admin_header.php';
?>

<div class="container-fluid">
    <h1 class="mt-4">Promo Code Wise Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Promo Code Wise Sales Report</li>
    </ol>

     <!-- Sub-navigation for reports -->
    <div class="mb-4">
        <a href="reports.php" class="btn btn-outline-primary me-2">Sales Report</a>
        <a href="reports_date_wise.php" class="btn btn-outline-primary me-2">Date Wise</a>
        <a href="reports_day_wise.php" class="btn btn-outline-primary me-2">Day Wise</a>
        <a href="reports_category_wise.php" class="btn btn-outline-primary me-2">Category Wise</a>
        <a href="reports_product_wise.php" class="btn btn-outline-primary me-2">Product Wise</a>
        <a href="reports_user_wise.php" class="btn btn-outline-primary me-2">User Wise</a>
        <a href="reports_promo_code_wise.php" class="btn btn-primary">Promo Code Wise</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Sales Data by Promo Code
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Promo Code</th>
                            <th>Discount Type</th>
                            <th>Discount Value</th>
                            <th>Usage Count</th>
                            <th>Total Discount Applied</th>
                            <th>Total Sales Generated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SAVE10</td>
                            <td>Percentage</td>
                            <td>10%</td>
                            <td>150</td>
                            <td>$1500.00</td>
                            <td>$15000.00</td>
                        </tr>
                        <tr>
                            <td>FREESHIP</td>
                            <td>Fixed Amount</td>
                            <td>$5.00</td>
                            <td>200</td>
                            <td>$1000.00</td>
                            <td>$10000.00</td>
                        </tr>
                        <tr>
                            <td>NEWUSER20</td>
                            <td>Percentage</td>
                            <td>20%</td>
                            <td>75</td>
                            <td>$1800.00</td>
                            <td>$9000.00</td>
                        </tr>
                        <tr>
                            <td>SUMMERFUN</td>
                            <td>Percentage</td>
                            <td>15%</td>
                            <td>120</td>
                            <td>$1200.00</td>
                            <td>$8000.00</td>
                        </tr>
                        <tr>
                            <td>GET25OFF</td>
                            <td>Fixed Amount</td>
                            <td>$25.00</td>
                            <td>50</td>
                            <td>$1250.00</td>
                            <td>$5000.00</td>
                        </tr>
                        <tr>
                            <td>WELCOMEBACK</td>
                            <td>Percentage</td>
                            <td>5%</td>
                            <td>180</td>
                            <td>$900.00</td>
                            <td>$18000.00</td>
                        </tr>
                        <tr>
                            <td>FLASHDEAL</td>
                            <td>Percentage</td>
                            <td>30%</td>
                            <td>40</td>
                            <td>$1200.00</td>
                            <td>$4000.00</td>
                        </tr>
                        <tr>
                            <td>LOYALTY10</td>
                            <td>Fixed Amount</td>
                            <td>$10.00</td>
                            <td>100</td>
                            <td>$1000.00</td>
                            <td>$10000.00</td>
                        </tr>
                        <tr>
                            <td>HOLIDAY20</td>
                            <td>Percentage</td>
                            <td>20%</td>
                            <td>90</td>
                            <td>$2000.00</td>
                            <td>$10000.00</td>
                        </tr>
                        <tr>
                            <td>SAVEBIG</td>
                            <td>Fixed Amount</td>
                            <td>$50.00</td>
                            <td>30</td>
                            <td>$1500.00</td>
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