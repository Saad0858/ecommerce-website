<?php
/* =====================================================
 *  ADMIN  –  TOP NAVIGATION  (Bootstrap 5 + custom skin)
 * ===================================================== */
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
  <div class="container-fluid">

      <!-- Brand -->
      <a class="navbar-brand fw-bold" href="dashboard.php">
          <i class="bi bi-speedometer2"></i> EyeStore Admin
      </a>

      <!-- Toggler (mobile) -->
      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse"
              data-bs-target="#adminNav"
              aria-controls="adminNav"
              aria-expanded="false"
              aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Links -->
      <div class="collapse navbar-collapse" id="adminNav">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center text-lg-start">

              <?php
              /* Build the nav array once; easy to reorder or add items */
              $navItems = [
                  ['Dashboard',  'dashboard.php',        'bi-speedometer2'],
                  ['Products',   'products.php',         'bi-box-seam'],
                  ['Inventory',  'inventory.php',        'bi-box'],
                  ['Batch Update','inventory_import.php','bi-cloud-upload'],
                  ['Orders',     'orders.php',           'bi-cart-check'],
                  ['Users',      'users.php',            'bi-people'],
                  ['Promo Codes','promo_codes.php',      'bi-tag'],
                  ['Reports',    'reports.php',          'bi-graph-up'],
              ];

              foreach ($navItems as [$title, $url, $icon]): ?>
                  <li class="nav-item">
                      <a class="nav-link px-3 py-2 rounded-2
                         <?= (basename($_SERVER['PHP_SELF']) === $url) ? 'active' : '' ?>"
                         href="<?= $url ?>">
                          <i class="bi <?= $icon ?> me-1"></i>
                          <?= $title ?>
                      </a>
                  </li>
              <?php endforeach; ?>

              <!-- Divider + Logout -->
              <li><hr class="dropdown-divider d-lg-none"></li>
              <li class="nav-item">
                  <a class="nav-link text-warning px-3 py-2 rounded-2"
                     href="../backend/logout.php">
                      <i class="bi bi-box-arrow-right me-1"></i> Logout
                  </a>
              </li>
          </ul>
      </div>
  </div>
</nav>

<!-- ====== Bootstrap 5 Icons (optional but nice) ====== -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">