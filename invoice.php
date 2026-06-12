<?php
include 'db.php';
session_start();

// Login စစ်မယ်
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

// အော်ဒါအချက်အလက်ကို Database ထဲက ဆွဲထုတ်မယ်
$sql = "SELECT * FROM orders WHERE id = '$order_id'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if(!$order){
    echo "အော်ဒါရှာမတွေ့ပါ။";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order['id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .invoice-box {
            max-width: 700px;
            margin: 50px auto;
            padding: 40px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .invoice-header { border-bottom: 2px solid #6c5ce7; padding-bottom: 20px; margin-bottom: 30px; }
        .brand-name { color: #6c5ce7; font-weight: 800; font-size: 28px; }
        .table thead { background: #6c5ce7; color: white; }
        .total-section { background: #f8f9fa; padding: 20px; border-radius: 10px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="invoice-header d-flex justify-content-between align-items-center">
        <div>
            <div class="brand-name">SMART COURIER</div>
            <p class="text-muted mb-0">အမြန်ဆုံးနှင့် စိတ်ချရသော ပို့ဆောင်ရေး</p>
        </div>
        <div class="text-end">
            <h4 class="mb-0">INVOICE</h4>
            <p class="text-muted mb-0">Order ID: #<?php echo $order['id']; ?></p>
            <p class="text-muted mb-0">Date: <?php echo date('d-M-Y'); ?></p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="fw-bold">Payment Details:</h6>
            <p class="small text-muted mb-0">Status: <span class="badge bg-success"><?php echo $order['status']; ?></span></p>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Product Description</th>
                <th class="text-end">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                <td class="text-end"><?php echo number_format($order['amount']); ?> Ks</td>
            </tr>
        </tbody>
    </table>

    <div class="row justify-content-end">
        <div class="col-5">
            <div class="total-section text-end">
                <p class="mb-1 fw-bold text-muted">Total Amount:</p>
                <h4 class="text-primary fw-bold"><?php echo number_format($order['amount']); ?> Ks</h4>
            </div>
        </div>
    </div>

    <hr class="my-4">
    <div class="text-center">
        <p class="small text-muted mb-4">ကျွန်ုပ်တို့၏ ဝန်ဆောင်မှုကို ယုံကြည်စွာ အသုံးပြုသည့်အတွက် ကျေးဇူးတင်ပါသည်။</p>
        
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-primary px-4 me-2">
                <i class="fas fa-print me-1"></i> Print / Save PDF
            </button>
            <a href="dashboard.php" class="btn btn-outline-secondary px-4">Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>