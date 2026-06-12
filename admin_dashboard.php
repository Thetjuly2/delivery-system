<?php
include 'db.php';
session_start();

if(!isset($_SESSION['admin_logged_in'])){ 
    header("Location: admin_login.php"); 
    exit(); 
}

// 🎯 Stock Update လုပ်မယ့် Logic (စိတ်ကြိုက်အရေအတွက် ပေါင်းထည့်ခြင်း)
if(isset($_POST['update_stock'])){
    $p_id = $_POST['p_id'];
    $new_qty = intval($_POST['qty']); // ရိုက်ထည့်လိုက်တဲ့ အရေအတွက်ကို ယူမယ်
    
    if($new_qty > 0){
        $update_sql = "UPDATE products SET stock = stock + $new_qty WHERE id = '$p_id'";
        if(mysqli_query($conn, $update_sql)){
            echo "<script>alert('ပစ္စည်းအရေအတွက် $new_qty ခု ထပ်ပေါင်းထည့်ပြီးပါပြီ။'); window.location.href='admin_dashboard.php';</script>";
            exit();
        }
    }
}

// ၁။ Total Orders ရှာခြင်း
$order_res = mysqli_query($conn, "SELECT id FROM orders");
$total_orders = ($order_res) ? mysqli_num_rows($order_res) : 0;

// ၂။ Revenue ရှာခြင်း
$rev_res = mysqli_query($conn, "SELECT SUM(amount) as total FROM orders WHERE status='Completed' OR status='Delivered'");
$revenue_query = ($rev_res) ? mysqli_fetch_assoc($rev_res) : null;
$total_revenue = $revenue_query['total'] ?? 0;

// ၃။ Low Stock Items ရှာခြင်း
$low_stock_res = mysqli_query($conn, "SELECT id FROM products WHERE stock <= 5");
$low_stock_count = ($low_stock_res) ? mysqli_num_rows($low_stock_res) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Poppins', sans-serif; }
        .sidebar { width: 250px; height: 100vh; background: #2c3e50; color: white; position: fixed; display: flex; flex-direction: column; }
        .main-content { margin-left: 250px; padding: 40px; }
        .nav-link { color: #bdc3c7; padding: 12px 25px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: #1abc9c; border-left: 4px solid #1abc9c; }
        .card-stat { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table-card { border-radius: 15px; border: none; background: white; }
        .stock-input { width: 80px; display: inline-block; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="p-4 text-center border-bottom border-secondary">
        <h4 class="fw-bold mb-0 text-white">Admin Panel</h4>
    </div>
    <div class="nav flex-column mt-3">
        <a href="index.php" class="nav-link"><i class="fas fa-home"></i> View Website</a>
        <a href="admin_dashboard.php" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="manage_orders.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Manage Orders</a>
        <a href="manage_products.php" class="nav-link"><i class="fas fa-box"></i> Manage Products</a>
         <a href="view_reviews.php" class="nav-link">
            <i class="fas fa-comments"></i> View Reviews
        </a>
        <a href="admin_settings.php" class="nav-link">
            <i class="fas fa-user-cog"></i> Admin Settings
        </a>
    
        <a href="admin_logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <h2 class="mb-4 fw-bold">Inventory Control</h2>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card card-stat bg-primary text-white p-4">
                <h6>Total Orders</h6><h2 class="fw-bold"><?php echo $total_orders; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-success text-white p-4">
                <h6>Revenue</h6><h2 class="fw-bold"><?php echo number_format($total_revenue); ?> Ks</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-warning text-dark p-4">
                <h6>Low Stock</h6><h2 class="fw-bold"><?php echo $low_stock_count; ?></h2>
            </div>
        </div>
    </div>
    <div class="card table-card shadow-sm">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold mb-0">Stock Management</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-4">Product Name</th>
                        <th>Sold (ရောင်းပြီး)</th>
                        <th>Stock (လက်ကျန်)</th>
                        <th>Update Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM products");
                    while($row = mysqli_fetch_assoc($res)):
                    ?>
                    <tr>
                        <td class="text-start ps-4 fw-bold"><?php echo $row['name']; ?></td>
                        <td class="text-success fw-bold"><?php echo $row['sold_count']; ?></td>
                        <td class="text-primary fw-bold"><?php echo $row['stock']; ?></td>
                        <td>
                            <form method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                <input type="hidden" name="p_id" value="<?php echo $row['id']; ?>">
                                <input type="number" name="qty" class="form-control form-control-sm stock-input" placeholder="Qty" min="1" required>
                                <button type="submit" name="update_stock" class="btn btn-sm btn-success rounded-pill">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>