<?php
include 'db.php';

if(isset($_POST['add_stock'])){
    $p_id = $_POST['product_id'];
    $add_qty = $_POST['quantity']; // ထပ်ပေါင်းမည့်အရေအတွက် (ဥပမာ 50)

    // လက်ရှိရှိနေတဲ့အထဲကို ပေါင်းထည့်မယ်
    $sql = "UPDATE products SET stock = stock + $add_qty WHERE id = '$p_id'";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('ပစ္စည်းအရေအတွက် ထပ်ဖြည့်ပြီးပါပြီ။'); window.location.href='admin_dashboard.php';</script>";
    }
}
?>