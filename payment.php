
<?php
include 'db.php';
session_start();

// ၁။ Session ထဲမှာ ပစ္စည်းရှိမရှိ အရင်စစ်မယ်
$total = 0;
$item_count = 0;

if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $total += $item['price'] * $item['quantity'];
        $item_count += $item['quantity'];
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Smart Courier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .payment-card { max-width: 500px; margin: 40px auto; background: white; border-radius: 20px; padding: 25px; border: none; }
        .summary-box { background: #f0f7ff; border-radius: 15px; padding: 20px; margin-bottom: 25px; border: 1px dashed #0d6efd; }
        .pay-option { border: 2px solid #f0f0f0; border-radius: 15px; padding: 15px; margin-bottom: 15px; cursor: pointer; transition: 0.3s; }
        .pay-option:hover { border-color: #6c5ce7; background-color: #fcfaff; }
        .pay-option.active { border-color: #6c5ce7; background-color: #fcfaff; }
        .pay-logo { width: 45px; height: 45px; border-radius: 10px; margin-right: 15px; }
        #payment-details { display: none; }
        .qr-img { width: 180px; height: 180px; border-radius: 10px; margin-top: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="container">
    <div class="payment-card shadow-lg">
        <h4 class="fw-bold text-center mb-4">Make Payment</h4>

        <div class="summary-box text-center">
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Items:</span>
                <span class="fw-bold"><?php echo $item_count; ?> Units</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Total Amount:</span>
                <h4 class="fw-bold text-success mb-0"><?php echo number_format($total); ?> Ks</h4>
            </div>
        </div>

        <form action="process_order.php" method="POST" enctype="multipart/form-data">
            
            <label class="fw-bold small mb-2 text-muted text-uppercase">Select Payment Method</label>
            
            <div class="pay-option d-flex align-items-center" onclick="selectPay('KBZPay', 'https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg', this)">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQoYwvrVyhKYaKGnbXB-OsEp3718xqo4JKeEA&s" class="pay-logo">
                <div class="text-start">
                    <h6 class="mb-0 fw-bold">KBZPay</h6>
                    <small class="text-muted">Instant Transfer</small>
                </div>
                <i class="fas fa-check-circle ms-auto text-muted"></i>
            </div>

            <div class="pay-option d-flex align-items-center" onclick="selectPay('WavePay', 'https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg', this)">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQa-eGbGX8CRnHu5ttemN_FoDWb2bJkdRYCVg&s" class="pay-logo">
                <div class="text-start">
                    <h6 class="mb-0 fw-bold">WavePay</h6>
                    <small class="text-muted">Fast & Easy</small>
                </div>
                <i class="fas fa-check-circle ms-auto text-muted"></i>
            </div>

            <div id="payment-details" class="alert alert-secondary border-0 text-center mb-4 p-3 shadow-sm rounded-4">
                <p class="small mb-1 fw-bold text-dark" id="method-title"></p>
                <img src="" id="pay-qr" class="qr-img mb-2 shadow-sm" style="display:none;">
                <br>
                <small class="text-danger fw-bold">* QR ကို Scan ဖတ်၍ ငွေလွှဲပြီးမှ Screenshot တင်ပါ</small>
            </div>
            <div class="mb-3">
                <label class="small fw-bold mb-1">ဆက်သွယ်ရန်ဖုန်း</label>
                <input type="text" name="phone" class="form-control form-control-lg fs-6" required placeholder="09xxxxxxxxx">
            </div>
            <div class="mb-3">
                <label class="small fw-bold mb-1">ပို့ဆောင်ရမည့်လိပ်စာ</label>
                <textarea name="address" class="form-control" rows="2" required placeholder="အိမ်အမှတ်၊ လမ်း၊ မြို့နယ်..."></textarea>
            </div>
            <div class="mb-4">
                <label class="small fw-bold mb-1">ငွေလွှဲ Screenshot တင်ပါ</label>
                <input type="file" name="screenshot" class="form-control shadow-sm" required>
            </div>

            <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
            <button type="submit" name="confirm_order" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg">
                မှာယူမှုကို အတည်ပြုပါ
            </button>
        </form>
    </div>
</div>

<script>
function selectPay(name, qrImage, element) {
    // Reset active states
    document.querySelectorAll('.pay-option').forEach(opt => {
        opt.classList.remove('active');
        opt.querySelector('.fa-check-circle').classList.replace('text-primary', 'text-muted');
    });

    // Set active state
    element.classList.add('active');
    element.querySelector('.fa-check-circle').classList.replace('text-muted', 'text-primary');

    // Show details and QR
    const detailBox = document.getElementById('payment-details');
    const qrImgTag = document.getElementById('pay-qr');
    
    detailBox.style.display = 'block';
    document.getElementById('method-title').innerText = name + " QR Scan ဖတ်ရန်";
    
    qrImgTag.src = qrImage; // QR ပုံလမ်းကြောင်းကို ပြောင်းပေးသည်
    qrImgTag.style.display = 'inline-block';
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>