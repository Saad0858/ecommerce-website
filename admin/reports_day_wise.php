<?php
include 'admin_header.php';
?>

<div class="container-fluid">
    <h1 class="mt-4">Day Wise Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Day Wise Sales Report</li>
    </ol>

    <!-- Sub-navigation for reports -->
    <div class="mb-4">
        <a href="reports.php" class="btn btn-outline-primary me-2">Sales Report</a>
        <a href="reports_date_wise.php" class="btn btn-outline-primary me-2">Date Wise</a>
        <a href="reports_day_wise.php" class="btn btn-primary me-2">Day Wise</a>
        <a href="reports_category_wise.php" class="btn btn-outline-primary me-2">Category Wise</a>
        <a href="reports_product_wise.php" class="btn btn-outline-primary me-2">Product Wise</a>
        <a href="reports_user_wise.php" class="btn btn-outline-primary me-2">User Wise</a>
        <a href="reports_promo_code_wise.php" class="btn btn-outline-primary">Promo Code Wise</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Sales Data by Day of Week
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Day of Week</th>
                            <th>Total Orders</th>
                            <th>Total Sales</th>
                            <th>Average Order Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monday</td>
                            <td>25</td>
                            <td>$2800.00</td>
                            <td>$112.00</td>
                        </tr>
                        <tr>
                            <td>Tuesday</td>
                            <td>30</td>
                            <td>$3500.00</td>
                            <td>$116.67</td>
                        </tr>
                        <tr>
                            <td>Wednesday</td>
                            <td>20</td>
                            <td>$2100.00</td>
                            <td>$105.00</td>
                        </tr>
                        <tr>
                            <td>Thursday</td>
                            <td>35</td>
                            <td>$4000.00</td>
                            <td>$114.29</td>
                        </tr>
                        <tr>
                            <td>Friday</td>
                            <td>40</td>
                            <td>$4800.00</td>
                            <td>$120.00</td>
                        </tr>
                        <tr>
                            <td>Saturday</td>
                            <td>50</td>
                            <td>$6000.00</td>
                            <td>$120.00</td>
                        </tr>
                        <tr>
                            <td>Sunday</td>
                            <td>45</td>
                            <td>$5200.00</td>
                            <td>$115.56</td>
                        </tr>
                        <tr>
                            <td>Monday</td>
                            <td>28</td>
                            <td>$3100.00</td>
                            <td>$110.71</td>
                        </tr>
                        <tr>
                            <td>Tuesday</td>
                            <td>32</td>
                            <td>$3700.00</td>
                            <td>$115.63</td>
                        </tr>
                        <tr>
                            <td>Wednesday</td>
                            <td>23</td>
                            <td>$2400.00</td>
                            <td>$104.35</td>
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