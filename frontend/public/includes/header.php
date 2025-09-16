<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6c757d;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #1cc88a;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 70px;
        }
        
        .navbar-custom {
            background: linear-gradient(90deg, var(--dark-color) 0%, #2c3e50 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .site-title {
            font-weight: 700;
            font-size: 1.8rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .site-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-item {
            margin: 0 5px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: 500;
            display: inline-block;
        }
        
        .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link i {
            margin-right: 8px;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1607082350899-7e105aa886ae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 40px;
            border-radius: 0 0 10px 10px;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 25px;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-body {
            padding: 20px;
        }
        
        .product-title {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .product-price {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.3rem;
            margin: 10px 0;
        }
        
        .btn-add-cart {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s;
            width: 100%;
        }
        
        .btn-add-cart:hover {
            background-color: #3a5fce;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }
        
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .social-icons {
            margin-top: 20px;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: var(--primary-color);
        }
        
        .user-status {
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-left: 15px;
            font-weight: 500;
        }
        
        .user-logged-in {
            background-color: var(--success-color);
        }
        
        .user-logged-out {
            background-color: var(--secondary-color);
        }
        
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
            }
            
            .nav-menu {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .nav-item {
                margin: 5px;
            }
            
            .user-status {
                margin: 10px 0 0 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar-custom fixed-top">
        <div class="nav-container">
            <a href="#" class="site-title">
                <i class="fas fa-store"></i>E-Commerce Store
            </a>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/products.php" class="nav-link">
                        <i class="fas fa-home"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/cart.php" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>Cart
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/account.php" class="nav-link">
                            <i class="fas fa-user"></i>Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/../../backend/logout.php" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </a>
                    </li>
                    <li class="user-status user-logged-in">
                        <i class="fas fa-check-circle"></i> Logged In
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/login.php" class="nav-link">
                            <i class="fas fa-sign-in-alt"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/register.php" class="nav-link">
                            <i class="fas fa-user-plus"></i>Register
                        </a>
                    </li>
                    <li class="user-status user-logged-out">
                        <i class="fas fa-times-circle"></i> Guest
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    

    

    <!-- Font Awesome Script -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>