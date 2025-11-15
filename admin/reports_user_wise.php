<?php
include 'admin_header.php';
?>

<div class="container-fluid">
    <h1 class="mt-4">User Wise Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">User Wise Sales Report</li>
    </ol>

    <!-- Sub-navigation for reports -->
    <div class="mb-4">
        <a href="reports.php" class="btn btn-outline-primary me-2">Sales Report</a>
        <a href="reports_date_wise.php" class="btn btn-outline-primary me-2">Date Wise</a>
        <a href="reports_day_wise.php" class="btn btn-outline-primary me-2">Day Wise</a>
        <a href="reports_category_wise.php" class="btn btn-outline-primary me-2">Category Wise</a>
        <a href="reports_product_wise.php" class="btn btn-outline-primary me-2">Product Wise</a>
        <a href="reports_user_wise.php" class="btn btn-primary">User Wise</a>
        <a href="reports_promo_code_wise.php" class="btn btn-outline-primary">Promo Code Wise</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Sales Data by User
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Last Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>101</td>
                            <td>john.doe</td>
                            <td>5</td>
                            <td>$750.00</td>
                            <td>2023-11-15</td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>jane.smith</td>
                            <td>8</td>
                            <td>$1200.50</td>
                            <td>2023-11-20</td>
                        </tr>
                        <tr>
                            <td>103</td>
                            <td>peter.jones</td>
                            <td>3</td>
                            <td>$300.75</td>
                            <td>2023-10-28</td>
                        </tr>
                        <tr>
                            <td>104</td>
                            <td>alice.wong</td>
                            <td>10</td>
                            <td>$2500.00</td>
                            <td>2023-12-01</td>
                        </tr>
                        <tr>
                            <td>105</td>
                            <td>bob.brown</td>
                            <td>2</td>
                            <td>$150.20</td>
                            <td>2023-09-01</td>
                        </tr>
                        <tr>
                            <td>106</td>
                            <td>charlie.davis</td>
                            <td>7</td>
                            <td>$900.00</td>
                            <td>2023-11-25</td>
                        </tr>
                        <tr>
                            <td>107</td>
                            <td>diana.miller</td>
                            <td>12</td>
                            <td>$3000.00</td>
                            <td>2023-12-05</td>
                        </tr>
                        <tr>
                            <td>108</td>
                            <td>eve.taylor</td>
                            <td>4</td>
                            <td>$450.00</td>
                            <td>2023-10-10</td>
                        </tr>
                        <tr>
                            <td>109</td>
                            <td>frank.white</td>
                            <td>6</td>
                            <td>$800.00</td>
                            <td>2023-11-01</td>
                        </tr>
                        <tr>
                            <td>110</td>
                            <td>grace.green</td>
                            <td>9</td>
                            <td>$1500.00</td>
                            <td>2023-11-30</td>
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