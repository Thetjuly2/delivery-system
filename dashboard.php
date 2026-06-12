<?php
include 'db.php';
session_start();

// Login စစ်ဆေးခြင်း
if(!isset($_SESSION['user_id'])){ 
    header("Location: login.php"); 
    exit(); 
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Database မှ ပစ္စည်းများ ဆွဲထုတ်ခြင်း
$res = mysqli_query($conn, "SELECT * FROM products ORDER BY category ASC");
$inventory = [];
if ($res && mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)){
        $inventory[$row['category']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Courier - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .navbar { background-color: #6c5ce7; }
        .category-title { border-left: 5px solid #6c5ce7; padding-left: 15px; margin: 40px 0 20px; font-weight: bold; color: #333; }
        .product-card { border: none; border-radius: 15px; transition: 0.3s; overflow: hidden; background: #fff; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(108, 92, 231, 0.2); }
        .product-img { height: 160px; width: 100%; object-fit: cover; }
        .cart-badge { position: absolute; top: -5px; right: -10px; font-size: 0.7rem; }
        /* ခလုတ်လေးတွေ အဝိုင်းဖြစ်အောင် style ထပ်ပေါင်းထားတယ် */
        .nav-btn-custom { border-radius: 50px !important; padding: 5px 15px !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php"><i class="fas fa-box-open me-2"></i>Online Shopping and Inventory Management System</a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="dashboardNav">
            <div class="navbar-nav ms-auto align-items-center gap-2">
                <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill px-3 nav-btn-custom active">
                    <i class="fas fa-home me-1"></i> Home
                </a>
                
                <a href="review.php" class="btn btn-outline-light btn-sm rounded-pill px-3 nav-btn-custom">
                    <i class="fas fa-star me-1"></i> Review
                </a>
                <a href="my_review.php" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-comment-dots me-2"></i> My Reviews
                </a>

                <a href="cart.php" class="btn btn-warning btn-sm rounded-pill px-3 shadow position-relative nav-btn-custom">
                    <i class="fas fa-shopping-cart me-1"></i> Cart
                    <span class="badge rounded-pill bg-danger cart-badge">
                        <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                    </span>
                </a>
                
                <a href="track.php" class="btn btn-outline-light btn-sm rounded-pill px-3 nav-btn-custom">Track Order</a>
                
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown">
                        <?php echo htmlspecialchars($user_name); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="container pb-5">
    <?php if(empty($inventory)): ?>
        <div class="text-center mt-5 py-5">
            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
            <p class="text-muted">No products available.</p>
        </div>
    <?php else: ?>
        <?php foreach($inventory as $category => $items): ?>
            <h3 class="category-title text-uppercase"><?php echo htmlspecialchars($category); ?></h3>
            <div class="row g-4">
                <?php foreach($items as $item): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card product-card h-100 shadow-sm">
                        <img src="<?php echo $item['image']; ?>" class="product-img" onerror="this.src='https://via.placeholder.com/300x160?text=No+Image'">
                        <div class="card-body d-flex flex-column text-center">
                            <h6 class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></h6>
                            <p class="text-primary fw-bold mb-2"><?php echo number_format($item['price']); ?> Ks</p>
                            
                            <form action="add_to_cart.php" method="POST" class="mt-auto">
                                <input type="hidden" name="p_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="p_name" value="<?php echo $item['name']; ?>">
                                <input type="hidden" name="p_price" value="<?php echo $item['price']; ?>">
                                
                                <div class="input-group input-group-sm mb-2 px-3">
                                    <span class="input-group-text bg-light">Qty</span>
                                    <input type="number" name="p_qty" value="1" min="1" class="form-control text-center">
                                </div>
                                
                                <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm w-100 rounded-pill shadow-sm">
                                    <i class="fas fa-cart-plus me-1"></i> Add To Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>