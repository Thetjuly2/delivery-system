<?php
include 'db.php';
session_start();

// ၁။ Admin Login စစ်ဆေးခြင်း
if(!isset($_SESSION['admin_logged_in'])){ 
    header("Location: admin_login.php"); 
    exit(); 
}

// ၂။ Status Update လုပ်သည့် Logic
if(isset($_POST['update_st'])){
    $oid = mysqli_real_escape_string($conn, $_POST['oid']); 
    $st = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "UPDATE orders SET status='$st' WHERE id='$oid'");
    header("Location: manage_orders.php");
    exit();
}

// ၃။ Orders table ကို Users နှင့် Join ထုတ်မည်
$sql = "SELECT orders.*, users.username 
        FROM orders 
        INNER JOIN users ON orders.user_id = users.id 
        ORDER BY orders.id DESC";

$orders = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-card { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 25px; margin-top: 20px; }
        .table thead { background: #2c3e50; color: white; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="main-card">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h4 class="fw-bold"><i class="fas fa-shipping-fast me-2"></i> မှာယူမှုများ စီမံခန့်ခွဲရန်</h4>
            <a href="admin_dashboard.php" class="btn btn-dark btn-sm rounded-pill px-3">Dashboard သို့</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Items (ပစ္စည်းစာရင်း)</th>
                        <th>Phone & Address</th>
                        <th>Total (KS)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td class="fw-bold text-primary">#<?php echo $row['id']; ?></td>
                        <td><i class="fas fa-user me-1 text-muted"></i> <?php echo htmlspecialchars($row['username']); ?></td>
                        
                        <td>
                            <?php 
                                $oid = $row['id'];
                                $items_query = "SELECT p.name, oi.quantity 
                                                FROM order_items oi 
                                                JOIN products p ON oi.product_id = p.id 
                                                WHERE oi.order_id = '$oid'";
                                $items_res = mysqli_query($conn, $items_query);
                                while($item = mysqli_fetch_assoc($items_res)):
                            ?>
                                <div class="small text-dark">
                                    • <?php echo htmlspecialchars($item['name']); ?> <b>(x<?php echo $item['quantity']; ?>)</b>
                                </div>
                            <?php endwhile; ?>
                        </td>

                        <td>
                            <div class="small">
                                <strong>Ph:</strong> <?php echo htmlspecialchars($row['phone']); ?><br>
                                <strong>Add:</strong> <?php echo htmlspecialchars($row['address']); ?>
                            </div>
                        </td>
                        <td class="fw-bold">
                            <?php 
                                $total_sql = "SELECT SUM(oi.quantity * p.price) AS total 
                                              FROM order_items oi 
                                              JOIN products p ON oi.product_id = p.id 
                                              WHERE oi.order_id = '$oid'";
                                $total_res = mysqli_query($conn, $total_sql);
                                $total_row = mysqli_fetch_assoc($total_res);
                                echo number_format($total_row['total']); 
                            ?>
                        </td>

                        <td>
                            <?php 
                                $status = $row['status'];
                                $badge = ($status == 'Pending') ? 'bg-warning text-dark' : (($status == 'Completed') ? 'bg-success' : 'bg-danger');
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo $status; ?></span>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="oid" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="Pending" <?php if($status=='Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Completed" <?php if($status=='Completed') echo 'selected'; ?>>Completed</option>
                                    <option value="Cancelled" <?php if($status=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                                <input type="hidden" name="update_st" value="1">
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