<?php
include 'db.php';
session_start();
// Login မဝင်ရသေးရင် login.php သို့ ပြန်ပို့ပေးပါမယ်
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];

// Database ထဲမှ Data များကို Order အလိုက် ဆွဲထုတ်ခြင်း
$sql = "SELECT * FROM reviews 
        WHERE user_id = '$user_id' 
        ORDER BY id DESC";
$res = mysqli_query($conn, $sql);

if (!$res) {
    die("Database Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="mb-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <h2 class="mb-4 fw-bold"><i class="fas fa-history text-primary"></i> My Reviews</h2>
    
    <?php if(mysqli_num_rows($res) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($res)): ?>
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="card-title text-dark">
                        <i class="fas fa-shopping-bag me-2 text-secondary"></i> Order Reference: #<?php echo htmlspecialchars($row['order_id']); ?>
                        <?php 
                            $oid = htmlspecialchars($row['order_id']);
                            if ($oid != 0) {
                                echo "Order Reference: #" . $oid;
                            } else {
                                echo "Review Details"; // 0 ဖြစ်နေရင် "Order Reference: #0" အစား ဒါလေးပေါ်ပါမယ်
                            }
                        ?>
                    </h5>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <p class="mb-0 text-secondary"><strong>Your Comment:</strong> "<?php echo htmlspecialchars($row['comment']); ?>"</p>
                    </div>
                    
                    <?php if(!empty($row['admin_reply'])): ?>
                        <div style="background: #eef6ff; padding: 15px; border-radius: 8px; border-left: 5px solid #0d6efd; margin-top: 15px;">
                            <strong style="color: #0d6efd;"><i class="fas fa-user-tie"></i> Admin's Reply:</strong>
                            <p class="mb-0 mt-1 text-dark"><?php echo htmlspecialchars($row['admin_reply']); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="mt-3">
                            <span class="badge bg-secondary"><i class="fas fa-clock"></i> Pending for Admin response...</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">သင် Review ပေးထားခြင်း မရှိသေးပါ။</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>