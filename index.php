<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Courier - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-gradient: linear-gradient(135deg, #6c5ce7, #a29bfe); }
        body { font-family: 'Poppins', sans-serif; background: #75beb5; }
        
        /* Navbar Styling */
        .navbar { background: rgba(255, 255, 255, 0.9) !important; backdrop-filter: blur(10px); }
        .nav-link { font-weight: 500; color: #333 !important; transition: 0.3s; }
        .nav-link:hover { color: #6c5ce7 !important; }

        /* Floating Icons */
        .floating-icon { position: absolute; font-size: 2.5rem; color: #6c5ce7; opacity: 0.6; animation: float 4s infinite ease-in-out; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }

        /* Custom Cards */
        .card-custom { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: 0.3s; }
        .card-custom:hover { transform: translateY(-10px); }
        .icon-box { width: 60px; height: 60px; background: var(--primary-gradient); border-radius: 15px; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }

        .btn-glow { background: var(--primary-gradient); color: white; border-radius: 50px; padding: 10px 25px; border: none; transition: 0.3s; }
        .btn-glow:hover { box-shadow: 0 10px 20px rgba(108, 92, 231, 0.4); transform: scale(1.05); color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php" style="color: #6c5ce7;">
            <i class="fas fa-bolt me-2"></i>Online Shopping and Inventory Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="track.php">Track Order</a></li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-glow fw-bold" href="register.php">Register Now</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<section class="py-5" style="margin-top: 50px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge rounded-pill bg-light text-primary px-3 py-2 mb-3 shadow-sm"> Onlineshop System in Myanmar</span>
                <h1 class="display-4 fw-bold mb-4">Reliable Service <br>for Your Essentials</h1>
                <p class="text-muted fs-5 mb-5"> မြန်မာတစ်နိုင်ငံလုံး ဝန်ဆောင်မှုပေးသော အဆင့်မြင့်စနစ်ဖြစ်ပါတယ်။</p>
                <div class="d-flex gap-3">
                    <a href="register.php" class="btn btn-glow btn-lg px-4">Shop Now <i class="fas fa-shopping-cart ms-2"></i></a>
                    <a href="track.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Track Order</a>
                </div>
            </div>
            
            <div class="col-lg-6 position-relative text-center">
                <i class="fas fa-box floating-icon" style="top: -20px; left: 10%;"></i>
                <i class="fas fa-motorcycle floating-icon" style="top: 50%; right: -20px; color:#fdcb6e; animation-delay: 1s;"></i>
                <img src="https://img.freepik.com/premium-vector/cute-girl-online-shopping-mobile-phone-cartoon-art-illustration_56104-656.jpg" class="img-fluid rounded-5 shadow-lg" style="max-height: 450px;">
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="icon-box mx-auto mb-3"><i class="fas fa-shipping-fast"></i></div>
                    <h3>5k+</h3>
                    <p class="text-muted mb-0">Daily Deliveries</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="icon-box mx-auto mb-3" style="background: #fdcb6e;"><i class="fas fa-users"></i></div>
                    <h3>10k+</h3>
                    <p class="text-muted mb-0">Happy Clients</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="icon-box mx-auto mb-3" style="background: #55efc4;"><i class="fas fa-warehouse"></i></div>
                    <h3>20+</h3>
                    <p class="text-muted mb-0">Warehouses</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="icon-box mx-auto mb-3" style="background: #ff7675;"><i class="fas fa-headset"></i></div>
                    <h3>24/7</h3>
                    <p class="text-muted mb-0">Support</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-5 bg-white border-top">
    <div class="container text-center">
        <div class="mb-4">
            <a href="login.php" class="btn btn-link text-decoration-none text-muted">Login</a>
            <a href="register.php" class="btn btn-link text-decoration-none text-muted">Register</a>
            <a href="track.php" class="btn btn-link text-decoration-none text-muted">Track Order</a>
            <a href="admin_login.php" class="btn btn-link text-decoration-none text-muted border-start ps-3">
                <i class="fas fa-user-shield"></i> Admin Staff
            </a>
        </div>
        <p class="text-muted">&copy; 2024 Online Tech. Made with Su Thet <i class="fas fa-heart text-danger"></i></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>