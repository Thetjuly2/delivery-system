<?php
session_start();

// Dashboard ကနေ Form Submit လုပ်ပြီး ပို့လိုက်တဲ့ အချက်အလက်ရှိမရှိ စစ်မယ်
if(isset($_POST['add_to_cart'])) {
    
    // ပို့လိုက်တဲ့ Data တွေကို Variable ထဲ ထည့်မယ်
    $product_id = $_POST['p_id'];
    $product_name = $_POST['p_name'];
    $product_price = $_POST['p_price'];
    $product_qty = $_POST['p_qty'];

    // အရင်ဆုံး 'cart' ဆိုတဲ့ Session တစ်ခု မရှိသေးရင် အသစ်ဆောက်မယ်
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // မှာယူလိုက်တဲ့ ပစ္စည်းက ခြင်းတောင်းထဲမှာ ရှိပြီးသားလား စစ်မယ်
    if(isset($_SESSION['cart'][$product_id])) {
        // ရှိပြီးသားဆိုရင် အဟောင်းထဲကို အခုမှာတဲ့ အရေအတွက် (Quantity) ထပ်ပေါင်းမယ်
        $_SESSION['cart'][$product_id]['quantity'] += $product_qty;
    } else {
        // မရှိသေးရင် ပစ္စည်းအသစ်အနေနဲ့ ခြင်းတောင်းထဲ ထည့်မယ်
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_qty
        );
    }

    // ပစ္စည်းထည့်ပြီးရင် Dashboard ဆီကိုပဲ ချက်ချင်း ပြန်လွှတ်လိုက်မယ်
    header("Location: dashboard.php");
    exit();

} else {
    // တကယ်လို့ ဒီဖိုင်ကို Form မနှိပ်ဘဲ တိုက်ရိုက်ဝင်လာရင် Dashboard ကို ပြန်မောင်းထုတ်မယ်
    header("Location: dashboard.php");
    exit();
}
?>