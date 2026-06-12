<?php
include 'db.php';
session_start();

// Admin Login မဝင်ထားရင် Login Page ကို ပြန်လွှတ်မယ်
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

// အော်ဒါ Status ပြောင်းလဲခြင်း
if(isset($_POST['update_st'])){
    $oid = $_POST['oid'];
    $st = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$st' WHERE id='$oid'");
    echo "<script>alert('Status Updated!'); window.location='manage_orders.php';</script>";
}

// စာရင်းချုပ်တွက်ချက်ခြင်း
$total_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count, SUM(amount) as total FROM orders"));
$orders = mysqli_query($conn, "SELECT orders.*, users.fullname FROM orders JOIN users ON orders.user_id = users.id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; display: flex; }
        .sidebar { width: 250px; height: 100vh; background: #2c3e50; color: white; position: fixed; padding: 20px; }
        .main-content { margin-left: 250px; width: 100%; padding: 40px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px; border-radius: 8px; margin-bottom: 10px; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; border-left: 4px solid #6c5ce7; }
        .card-summary { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="mb-4 text-center">📦 Admin Panel</h4>
    <a href="admin_dashboard.php"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
    <a href="manage_orders.php" class="active"><i class="fas fa-shopping-cart me-2"></i> Orders</a>
    <a href="manage_products.php"><i class="fas fa-box me-2"></i> Products</a>
    <hr>
    <a href="admin_logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Control Center</h2>
        <div class="text-muted">Total Orders: <b><?php echo $total_res['count']; ?></b></div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-summary bg-white p-3 d-flex flex-row align-items-center">
                <div class="bg-primary text-white p-3 rounded-circle me-3"><i class="fas fa-cash-register"></i></div>
                <div>
                    <h6 class="mb-0 text-muted">Total Sales</h6>
                    <h4 class="mb-0"><?php echo number_format($total_res['total']); ?> Ks</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-summary bg-white p-3 d-flex flex-row align-items-center">
                <div class="bg-warning text-white p-3 rounded-circle me-3"><i class="fas fa-clock"></i></div>
                <div>
                    <h6 class="mb-0 text-muted">Pending Tasks</h6>
                    <h4 class="mb-0">Active Control</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-summary bg-white p-4">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Product Item</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
                </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td><b>#<?php echo $row['id']; ?></b></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo number_format($row['amount']); ?> Ks</td>
                    <td>
                        <span class="badge rounded-pill <?php 
                            echo ($row['status']=='Pending') ? 'bg-warning' : (($row['status']=='On the Way') ? 'bg-primary' : 'bg-success'); 
                        ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" class="d-flex gap-2">
                            <input type="hidden" name="oid" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="On the Way" <?php if($row['status']=='On the Way') echo 'selected'; ?>>On the Way</option>
                                <option value="Delivered" <?php if($row['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                            <button name="update_st" class="btn btn-dark btn-sm rounded-pill px-3">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>