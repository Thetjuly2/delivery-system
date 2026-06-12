<?php
include 'db.php';
session_start();

// ပစ္စည်းဖယ်ရှားခြင်း Logic (အပေါ်မှာတင် တစ်ခါတည်းရေးထားပေးပါတယ်)
if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // Array index ပြန်စီပြီး Page ပြန်ဖွင့်မယ်
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

$grand_total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shopping Cart - Smart Courier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .cart-card { border-radius: 20px; border: none; overflow: hidden; }
        .btn-remove { color: #dc3545; transition: 0.3s; cursor: pointer; }
        .btn-remove:hover { color: #a71d2a; transform: scale(1.2); }
        .empty-cart-icon { font-size: 80px; color: #dee2e6; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0"><i class="fas fa-shopping-basket text-primary me-2"></i>ဈေးခြင်းတောင်း</h3>
        <a href="dashboard.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="fas fa-plus me-1"></i> ပစ္စည်းထပ်ဝယ်မည်
        </a>
    </div>

    <div class="card cart-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary small text-uppercase">
                            <th class="ps-4">ပစ္စည်းအမည်</th>
                            <th>ဈေးနှုန်း</th>
                            <th class="text-center">အရေအတွက်</th>
                            <th>စုစုပေါင်း</th>
                            <th class="text-center">ဖယ်ရှားရန်</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!empty($_SESSION['cart'])):
                            foreach($_SESSION['cart'] as $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $grand_total += $subtotal;
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo number_format($item['price']); ?> KS</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                    <?php echo $item['quantity']; ?>
                                </span>
                            </td>
                            <td class="fw-bold text-primary"><?php echo number_format($subtotal); ?> KS</td>
                            <td class="text-center">
                                <a href="cart.php?remove_id=<?php echo $item['id']; ?>" 
                                   class="btn-remove" 
                                   onclick="return confirm('ဤပစ္စည်းကို ဖယ်ရှားမှာ သေချာပါသလား?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                            </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="empty-cart-icon"><i class="fas fa-shopping-cart"></i></div>
                                <h5 class="text-muted">ခြင်းတောင်းထဲမှာ ပစ္စည်းမရှိသေးပါ။</h5>
                                <a href="dashboard.php" class="btn btn-primary rounded-pill mt-3 px-4">ပစ္စည်းသွားဝယ်မည်</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($grand_total > 0): ?>
            <div class="bg-light p-4 text-end">
                <p class="text-muted mb-1">စုစုပေါင်း ကျသင့်ငွေ</p>
                <h2 class="fw-bold text-success mb-4"><?php echo number_format($grand_total); ?> KS</h2>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="dashboard.php" class="btn btn-light rounded-pill px-4 fw-bold">ထပ်ဝယ်မည်</a>
                    <a href="payment.php" class="btn btn-primary btn-lg px-5 rounded-pill shadow fw-bold">
                        <i class="fas fa-credit-card me-2"></i> ငွေချေမည်
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>