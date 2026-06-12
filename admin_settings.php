<?php
include 'db.php';
session_start();

// Admin Login စစ်ဆေးခြင်း
if(!isset($_SESSION['admin_logged_in'])){ header("Location: admin_login.php"); exit(); }

$message = "";

if(isset($_POST['update_admin'])){
    $new_user = mysqli_real_escape_string($conn, $_POST['new_username']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if($new_pass === $confirm_pass){
        // Password ကို Hash လုပ်ပြီးသိမ်းတာ ပိုစိတ်ချရပါတယ်
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        
        // Admin table ထဲမှာ username နဲ့ password column ရှိရပါမယ်
        $sql = "UPDATE admin SET username='$new_user', password='$new_pass' WHERE id=1"; 
        
        if(mysqli_query($conn, $sql)){
            $message = "<div class='alert alert-success'>Admin အချက်အလက်များ အောင်မြင်စွာ ပြောင်းလဲပြီးပါပြီ။</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Password များ မတူညီပါ။ ပြန်စစ်ပေးပါ။</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Change Admin Login</h5>
                </div>
                <div class="card-body p-4">
                    <?php echo $message; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">New Username</label>
                            <input type="text" name="new_username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="update_admin" class="btn btn-dark w-100 py-2">Update Credentials</button>
                    </form>
                    <hr>
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary w-100">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>