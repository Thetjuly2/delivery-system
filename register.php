<?php
include 'db.php';
$error_msg = "";

if(isset($_POST['register'])){
    // Form ကလာတဲ့ Data တွေကို ယူမယ်
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
    // Database ထဲ သွင်းမယ် (Table ထဲမှာ fullname ဖြစ်နေတာ သေချာပါစေ)
    $query = "INSERT INTO users (username, email, password) VALUES ('$name', '$email', '$pass')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Register အောင်မြင်ပါသည်။ Login ဝင်နိုင်ပါပြီ။'); window.location='login.php';</script>";
    } else {
        // Error တက်ရင် ဘာကြောင့်လဲဆိုတာ ပြပေးမယ်
        $error_msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register - Smart Courier</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        }
        
        .register-container { 
            background: #fff; 
            padding: 40px; 
            border-radius: 25px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            width: 100%; 
            max-width: 400px; 
            text-align: center; 
            position: relative; /* Back button အတွက် position ပေးထားတာပါ */
        }

        /* Back Button Style */
        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: #764ba2;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }
        .back-home:hover {
            color: #667eea;
            transform: translateX(-5px);
        }

        h2 { color: #333; margin-bottom: 25px; font-weight: 600; margin-top: 10px; }
        
        .input-group { margin-bottom: 20px; }
        .input-group input { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #eee; 
            border-radius: 12px; 
            outline: none; 
            transition: 0.3s; 
            font-size: 14px;
        }
        .input-group input:focus { 
            border-color: #667eea; 
            box-shadow: 0 0 8px rgba(102, 126, 234, 0.2); 
        }

        button { 
            width: 100%; 
            padding: 12px; 
            border: none; 
            border-radius: 12px; 
            background: linear-gradient(to right, #667eea, #764ba2); 
            color: white; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.4s; 
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
        }
        button:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 5px 20px rgba(118, 75, 162, 0.5); 
        }

        .error-box { 
            background: #fee2e2; 
            color: #dc2626; 
            padding: 12px; 
            border-radius: 10px; 
            font-size: 13px; 
            margin-bottom: 20px; 
            text-align: left;
            border-left: 4px solid #dc2626;
        }

        .login-link { margin-top: 20px; font-size: 13px; color: #777; }
        .login-link a { color: #667eea; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-container">
    <a href="index.php" class="back-home">
        <i class="fas fa-arrow-left me-1"></i> Home
    </a>
    <h2>Create Account</h2>
    
    <?php if($error_msg != ""): ?>
        <div class="error-box">
            <i class="fas fa-exclamation-circle me-1"></i> <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <input type="text" name="name" placeholder="Full Name" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="input-group">
            <input type="password" name="pass" placeholder="Password" required>
        </div>
        <button type="submit" name="register">Sign Up Now</button>
    </form>
    
    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

</body>
</html>