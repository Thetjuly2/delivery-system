<?php
include 'db.php';
session_start();
$grand_total = 0;
if(empty($_SESSION['cart'])){ header("Location: index.php"); exit(); }
foreach($_SESSION['cart'] as $item){ $grand_total += ($item['price'] * $item['quantity']); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Delivery Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3 text-center">
                    <h5 class="mb-0">ပို့ဆောင်မည့် အချက်အလက်များ ဖြည့်ပါ</h5>
                </div>
                <div class="card-body p-4">
                    <form action="process_order.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ဖုန်းနံပါတ်</label>
                            <input type="text" name="phone" class="form-control" required placeholder="09xxxxxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ပို့ဆောင်ရမည့် လိပ်စာအပြည့်အစုံ</label>
                            <textarea name="address" class="form-control" rows="4" required placeholder="အိမ်အမှတ်၊ လမ်း၊ မြို့နယ်..."></textarea>
                        </div>
                        <div class="p-3 bg-light rounded mb-4 text-end">
                            <span class="text-muted">စုစုပေါင်းပေးချေရမည့်ငွေ -</span>
                            <h4 class="mb-0"><?php echo number_format($grand_total); ?> KS</h4>
                            <input type="hidden" name="total_price" value="<?php echo $grand_total; ?>">
                        </div>
                        <button type="submit" name="place_order" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow">
                            မှာယူမှုကို အတည်ပြုပါ <i class="fas fa-check-circle ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>