<?php
include 'db.php';
session_start();

if(!isset($_SESSION['user_id'])){ 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if(!$order){
    echo "<script>alert('မှာယူထားသော မှတ်တမ်းမရှိသေးပါ။'); window.location.href='dashboard.php';</script>";
    exit();
}
$status = $order['status']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking - Smart Courier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Background ပုံ ပေါ်စေရန် အပိုင်း */
        body { 
            /* မှတ်ချက် - Editer ကြီးရဲ့ index.php ထဲက ပုံနာမည်က bg.jpg ဆိုရင် 'bg.jpg' လို့ ပြောင်းရေးပါ */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://img.freepik.com/premium-vector/cute-girl-online-shopping-mobile-phone-cartoon-art-illustration_56104-656.jpg'); 
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .status-card { 
            background: rgba(255, 255, 255, 0.95); 
            border-radius: 25px; 
            padding: 40px; 
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            backdrop-filter: blur(5px);
        }

        .pulse-icon { 
            height: 12px; width: 12px; border-radius: 50%; 
            display: inline-block; animation: pulse 1.5s infinite; 
            margin-right: 8px;
        }

        @keyframes pulse { 
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(108, 92, 231, 0.7); } 
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(108, 92, 231, 0); } 
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(108, 92, 231, 0); } 
        }

        .eta-box {
            background: #f0edff;
            border-radius: 15px;
            padding: 20px;
            color: #6c5ce7;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center w-100 m-0">
        <div class="col-md-6 col-lg-5">
            <div class="status-card">
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 mb-4">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
                
                <h4 class="fw-bold mb-4" style="color: #6c5ce7;">
                    <i class="fas fa-truck-fast me-2"></i> Order Tracking
                </h4>
                
                <div class="order-info mb-4">
                    <p class="mb-2"><b>Order ID:</b> <span class="text-primary">#<?php echo $order['id']; ?></span></p>
                    <p class="mb-2"><b>Total Amount:</b> <?php echo number_format($order['amount']); ?> Ks</p>
                    <p class="mb-0">
                        <span class="pulse-icon" style="background: <?php echo ($status == 'Completed') ? '#28a745' : '#f39c12'; ?>;"></span> 
                        <b>Status:</b> 
                        <span style="color:<?php echo ($status == 'Completed') ? '#28a745' : '#f39c12'; ?>; font-weight: bold;">
                            <?php echo $status; ?>
                        </span>
                    </p>
                </div>
                
                <div class="eta-box mb-4">
                    <?php 
                        if($status == 'Shipped' || $status == 'On the Way') {
                            echo '<i class="fas fa-truck-moving me-2 fa-lg"></i> ယာဉ်မောင်းသူ လာနေပါပြီ...';
                        } elseif($status == 'Completed') {
                            echo '<i class="fas fa-check-circle me-2 fa-lg"></i> Ordersကို အတည်ပြုပြီးပါပြီ။';
                        } elseif($status == 'Confirmed') {
                            echo '<i class="fas fa-thumbs-up me-2 fa-lg"></i> Admin မှ အတည်ပြုပြီးပါပြီ။';
                        } else {
                            echo '<i class="fas fa-clock me-2 fa-lg"></i> စစ်ဆေးရန် စောင့်ဆိုင်းနေဆဲ...';
                        }
                    ?>
                </div>

                <?php if($status == 'Completed'): ?>
                    <div class="d-grid gap-2">
                        <a href="review.php" class="btn btn-primary rounded-pill py-2 shadow-sm">Review ပေးရန်</a>
                        <a href="invoice.php?order_id=<?php echo $order['id']; ?>" class="btn btn-outline-success rounded-pill py-2">ပြေစာကြည့်ရန်</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>