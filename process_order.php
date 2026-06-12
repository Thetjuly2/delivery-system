<?php
include 'db.php';
session_start();

if(isset($_POST['confirm_order'])){
    
    // ၁။ အချက်အလက်များ လက်ခံခြင်း
    $user_id = $_SESSION['user_id'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $total_price = $_POST['total_amount'];

    // ၂။ [အရေးကြီးဆုံးအဆင့်] ပစ္စည်းအားလုံး လက်ကျန်ရှိမရှိ အရင်ဆုံး စစ်ဆေးခြင်း 💡
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $item){
            $p_id = $item['id'];
            $qty = $item['quantity'];

            // Database ထဲက လက်ကျန်ကို တိုက်ရိုက်ဆွဲထုတ်စစ်မယ်
            $res = mysqli_query($conn, "SELECT name, stock FROM products WHERE id = '$p_id'");
            $product = mysqli_fetch_assoc($res);

            if($product['stock'] < $qty){
                // လက်ကျန်ထက် ပိုမှာရင် ဒီမှာတင် ရပ်မယ်၊ Database ထဲ ဘာမှမထည့်ဘူး
                echo "<script>
                        alert('မှာယူ၍မရပါ။ {$product['name']} က လက်ကျန် {$product['stock']} ခုပဲ ရှိပါတော့တယ်။');
                        window.history.back();
                      </script>";
                exit(); // ⛔ ဒီနေရာမှာ တင် အလုပ်အားလုံးကို ရပ်ပစ်လိုက်တာပါ
            }
        }
    }

    // ၃။ ပုံသိမ်းခြင်း (Stock အကုန်ရှိမှသာ ဒီအဆင့်ကို ရောက်မယ်)
    $screenshot_name = $_FILES['screenshot']['name'];
    $temp_name = $_FILES['screenshot']['tmp_name'];
    if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
    move_uploaded_file($temp_name, "uploads/" . $screenshot_name);

    // ၄။ Orders table ထဲ စာရင်းသွင်းခြင်း
    $sql_order = "INSERT INTO orders (user_id, address, phone, payment_screenshot, amount, status) 
                  VALUES ('$user_id', '$address', '$phone', '$screenshot_name', '$total_price', 'Pending')";
    
    if(mysqli_query($conn, $sql_order)){
        $order_id = mysqli_insert_id($conn);

        // ၅။ ပစ္စည်းတစ်ခုချင်းစီအတွက် Stock နှုတ်ခြင်းနှင့် Sold Count တိုးခြင်း
        foreach($_SESSION['cart'] as $item){
            $p_id = $item['id'];
            $qty = $item['quantity'];
            $price = $item['price'];

            // Order Items ထဲထည့်မယ်
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES ('$order_id', '$p_id', '$qty', '$price')");

            // Stock နှုတ်ပြီး Sold Count တိုးမယ်
            mysqli_query($conn, "UPDATE products 
                                 SET stock = stock - $qty, 
                                     sold_count = sold_count + $qty 
                                 WHERE id = '$p_id'");
        }

        // ခြင်းတောင်းကို ရှင်းမယ်
        unset($_SESSION['cart']);
        echo "<script>
                alert('မှာယူမှု အောင်မြင်ပါသည်။');
                window.location.href='dashboard.php';
              </script>";
        exit();

    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>