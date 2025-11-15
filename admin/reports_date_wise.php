<?php
include 'admin_header.php';
?>

<div class="container-fluid">
    <h1 class="mt-4">Date Wise Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Date Wise Sales Report</li>
    </ol>

    <!-- Sub-navigation for reports -->
    <div class="mb-4">
        <a href="reports.php" class="btn btn-outline-primary me-2">Sales Report</a>
        <a href="reports_date_wise.php" class="btn btn-primary me-2">Date Wise</a>
        <a href="reports_day_wise.php" class="btn btn-outline-primary me-2">Day Wise</a>
        <a href="reports_category_wise.php" class="btn btn-outline-primary me-2">Category Wise</a>
        <a href="reports_product_wise.php" class="btn btn-outline-primary me-2">Product Wise</a>
        <a href="reports_user_wise.php" class="btn btn-outline-primary me-2">User Wise</a>
        <a href="reports_promo_code_wise.php" class="btn btn-outline-primary">Promo Code Wise</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Sales Data by Date
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Orders</th>
                            <th>Total Sales</th>
                            <th>Average Order Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2023-01-01</td>
                            <td>15</td>
                            <td>$1500.00</td>
                            <td>$100.00</td>
                        </tr>
                        <tr>
                            <td>2023-01-02</td>
                            <td>20</td>
                            <td>$2200.50</td>
                            <td>$110.03</td>
                        </tr>
                        <tr>
                            <td>2023-01-03</td>
                            <td>10</td>
                            <td>$950.75</td>
                            <td>$95.08</td>
                        </tr>
                        <tr>
                            <td>2023-01-04</td>
                            <td>25</td>
                            <td>$3000.00</td>
                            <td>$120.00</td>
                        </tr>
                        <tr>
                            <td>2023-01-05</td>
                            <td>12</td>
                            <td>$1300.20</td>
                            <td>$108.35</td>
                        </tr>
                        <tr>
                            <td>2023-01-06</td>
                            <td>18</td>
                            <td>$1900.00</td>
                            <td>$105.56</td>
                        </tr>
                        <tr>
                            <td>2023-01-07</td>
                            <td>30</td>
                            <td>$3500.00</td>
                            <td>$116.67</td>
                        </tr>
                        <tr>
                            <td>2023-01-08</td>
                            <td>8</td>
                            <td>$700.00</td>
                            <td>$87.50</td>
                        </tr>
                        <tr>
                            <td>2023-01-09</td>
                            <td>22</td>
                            <td>$2400.00</td>
                            <td>$109.09</td>
                        </tr>
                        <tr>
                            <td>2023-01-10</td>
                            <td>17</td>
                            <td>$1850.00</td>
                            <td>$108.82</td>
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