<?php
include 'db.php';
session_start();

// ၁။ Status ကို Confirm, Complete သို့မဟုတ် Cancel လုပ်သည့် Logic
if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    if($action == 'confirm') {
        $status = 'Confirmed';
    } elseif($action == 'complete') {
        $status = 'Completed';
    } else {
        $status = 'Cancelled';
    }
    
    $update_query = "UPDATE orders SET status = '$status' WHERE id = '$id'";
    if(mysqli_query($conn, $update_query)){
        echo "<script>alert('Order #$id has been updated to $status'); window.location='admin_orders.php';</script>";
    }
}

// ၂။ Order အားလုံးကို ဆွဲထုတ်ခြင်း
$query = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Order List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .navbar { background: #2c3e50 !important; }
        .main-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-top: 30px; }
        .status-pending { color: orange; font-weight: bold; }
        .status-confirmed { color: blue; font-weight: bold; }
        .status-completed { color: green; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin_orders.php"><i class="fas fa-user-shield me-2"></i>Admin Panel</a>
        
        <div class="ms-auto d-flex align-items-center">
            <a href="index.php" class="btn btn-info btn-sm me-3 text-white fw-bold shadow-sm">
                <i class="fas fa-home me-1"></i> Back to Home
            </a>
            
            <a href="view_reviews.php" class="btn btn-outline-light btn-sm me-2">Reviews</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            <a href="track.php?order_id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm text-white" target="_blank">
    <i class="fas fa-map-marker-alt"></i> Track
</a>
        </div>
    </div>
</nav>

<div class="container main-container">
    <h2 class="mb-4 text-primary fw-bold"><i class="fas fa-shipping-fast me-2"></i>Incoming Orders</h2>
    
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo number_format($row['amount']); ?> Ks</td>
                    <td>
                        <?php 
                            $s = $row['status'];
                            $class = "status-pending";
                            if($s == 'Confirmed') $class = "status-confirmed";
                            if($s == 'Completed') $class = "status-completed";
                        ?>
                        <span class="<?php echo $class; ?>"><?php echo $s; ?></span>
                    </td>
                    <td>
                        <?php if($row['status'] == 'Pending') { ?>
                            <a href="?action=confirm&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Confirm</a>
                            <a href="?action=cancel&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                            <?php } elseif($row['status'] == 'Confirmed') { ?>
                            <a href="?action=complete&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Delivered</a>
                        
                        <?php } else { ?>
                            <button class="btn btn-secondary btn-sm" disabled>Finished</button>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>