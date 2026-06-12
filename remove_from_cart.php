<?php
session_start();
if(isset($_GET['id'])){
    $id = $_GET['id'];
    foreach($_SESSION['cart'] as $key => $item){
        if($item['id'] == $id){
            unset($_SESSION['cart'][$key]); // ထိုပစ္စည်းကို Session ထဲမှ ဖယ်ထုတ်ခြင်း
            break;
        }
    }
    // Array index တွေကို ပြန်စီပေးခြင်း
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
header("Location: cart.php");
exit();
?>