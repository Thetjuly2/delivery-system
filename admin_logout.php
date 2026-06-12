<?php
session_start();
// Admin နဲ့ဆိုင်တဲ့ session တွေကိုပဲ ဖျက်မယ်
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']); 

// Admin Login ဆီကိုပဲ တန်းပို့မယ်
header("Location: admin_login.php");
exit();
?>