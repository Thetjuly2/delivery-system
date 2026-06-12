<?php
include 'db.php';
session_start();

// Login စစ်ဆေးခြင်း
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

// URL ကနေ order_id ကို ဖမ်းထားခြင်း
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
$user_id = $_SESSION['user_id'];

// Review ပို့လိုက်သည့်အခါ
if(isset($_POST['submit_review'])){
    // 💡 အဓိကပြင်လိုက်တဲ့အပိုင်း: Hidden input ထဲကပို့တဲ့ order_id ကို ပြန်ဖမ်းတာပါ
    $order_id = $_POST['order_id']; 
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // reviews table ထဲသို့ သိမ်းဆည်းခြင်း
    $sql = "INSERT INTO reviews (order_id, user_id, rating, comment) 
            VALUES ('$order_id', '$user_id', '$rating', '$comment')";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Review ပေးပို့မှု အောင်မြင်ပါသည်။ ကျေးဇူးတင်ပါတယ်!'); window.location='dashboard.php';</script>";
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Review - Smart Courier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .review-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 450px; text-align: center; }
        .star-rating { color: #f1c40f; font-size: 2rem; margin-bottom: 20px; }
        .btn-submit { background: #6c5ce7; border: none; padding: 12px; border-radius: 12px; font-weight: 600; transition: 0.3s; }
        .btn-submit:hover { background: #5b4bc4; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="review-card">
    <div class="star-rating">
        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
    </div>
    <h3 class="fw-bold mb-2">Rate Your Service</h3>
    <p class="text-muted mb-4">Order #<?php echo htmlspecialchars($order_id); ?> အတွက် သင့်အမြင်ကို ရေးပေးပါ</p>

    <form method="POST">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">

        <div class="mb-3 text-start">
            <label class="form-label fw-bold">အဆင့်သတ်မှတ်ချက် ရွေးချယ်ပါ</label>
            <select name="rating" class="form-select form-select-lg" required>
                <option value="5">⭐️⭐️⭐️⭐️⭐️ အလွန်ကောင်းမွန်သည်</option>
                <option value="4">⭐️⭐️⭐️⭐️ ကောင်းမွန်သည်</option>
                <option value="3">⭐️⭐️⭐️ သင့်တင့်သည်</option>
                <option value="2">⭐️⭐️ ညံ့ဖျင်းသည်</option>
                <option value="1">⭐️ အလွန်ညံ့ဖျင်းသည်</option>
            </select>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label fw-bold">မှတ်ချက် ရေးသားရန်</label>
            <textarea name="comment" class="form-control" rows="4" placeholder="ဒီဝန်ဆောင်မှုအပေါ် ဘယ်လိုထင်မြင်ပါသလဲ..." required></textarea>
        </div>

        <button type="submit" name="submit_review" class="btn btn-primary btn-submit w-100 text-white">
            <i class="fas fa-paper-plane me-2"></i> Submit Review
        </button>
    </form>
</div>

</body>
</html>