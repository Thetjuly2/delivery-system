<?php
include 'db.php';
session_start();

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['pass'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($pass, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        // Role စစ်ဆေးတာကို database မှာ column မရှိရင် error မတက်အောင် default ထားမယ်
        $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Email သို့မဟုတ် Password မှားနေပါသည်။');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Courier</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Register နဲ့ လိုက်ဖက်မယ့် Gradient */
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 380px;
            text-align: center;
            transition: 0.3s;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        h2 {
            color: #4b3d8f;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 28px;
        }

        p.subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 13px 15px;
            border: 2px solid #f1f1f1;
            border-radius: 12px;
            outline: none;
            transition: 0.3s;
            background: #f9f9f9;
        }

        .input-group input:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.2);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(to right, #764ba2, #667eea);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.4s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
        }

        button:hover {
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(118, 75, 162, 0.5);
        }

        .footer-links {
            margin-top: 25px;
            font-size: 13px;
            color: #666;
        }

        .footer-links a {
            color: #764ba2;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Welcome</h2>
    <p class="subtitle">Login to your courier account</p>
    
    <form method="POST">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email Address" required>
            </div>
        <div class="input-group">
            <input type="password" name="pass" placeholder="Password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>
    
    <div class="footer-links">
        Don't have an account? <a href="register.php">Sign Up</a>
    </div>
</div>

</body>
</html>