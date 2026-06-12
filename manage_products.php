<?php
include 'db.php';
session_start();

// Admin Login စစ်ဆေးခြင်း
if(!isset($_SESSION['admin_logged_in'])){ 
    header("Location: admin_login.php"); 
    exit(); 
}

// ၁။ ပစ္စည်းသစ်ထည့်ခြင်း Logic (အမှားရှာရလွယ်အောင် Error reporting ပါထည့်ထားသည်)
if(isset($_POST['add_btn'])){
    $n = mysqli_real_escape_string($conn, $_POST['p_name']); 
    $p = $_POST['p_price']; 
    $c = $_POST['p_cat']; 
    $i = mysqli_real_escape_string($conn, $_POST['p_img']);
    
    $sql = "INSERT INTO products (name, price, category, image) VALUES ('$n', '$p', '$c', '$i')";
    
    if(mysqli_query($conn, $sql)){
        // ဝင်သွားရင် Page ကို Refresh လုပ်မယ်
        header("Location: manage_products.php?msg=success");
        exit();
    } else {
        // မဝင်ရင် ဘာကြောင့်လဲဆိုတာ ပြမယ်
        $error_msg = "Error: " . mysqli_error($conn);
    }
}

// ၂။ ပစ္စည်းဖျက်ခြင်း Logic
if(isset($_GET['del_id'])){
    $id = $_GET['del_id'];
    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: manage_products.php");
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Pyidaungsu', sans-serif; }
        .main-card { background: white; border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .form-control, .form-select { border-radius: 10px; padding: 10px; border: 1px solid #ddd; }
        .btn-add { border-radius: 10px; padding: 10px 25px; font-weight: bold; }
        .table img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="p-4">

<div class="container">
    <div class="d-flex align-items-center mb-4">
        <a href="admin_dashboard.php" class="btn btn-dark rounded-circle me-3 shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="mb-0">Product Management</h2>
    </div>

    <?php if(isset($error_msg)): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div class="card main-card p-4 mb-5">
        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-plus-circle me-2"></i>Add New Item</h5>
        <form method="POST" action="manage_products.php">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-bold">PRODUCT NAME</label>
                    <input type="text" name="p_name" class="form-control" placeholder="Enter name" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-bold">PRICE (KS)</label>
                    <input type="number" name="p_price" class="form-control" placeholder="0.00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted fw-bold">CATEGORY</label>
                    <select name="p_cat" class="form-select">
                        <option value="Electronics">Electronics</option>
                        <option value="Fast Food">Fast Food</option>
                        <option value="Grocery">Grocery</option>
                        <option value="Clothing">Clothing</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-bold">IMAGE URL</label>
                    <input type="text" name="p_img" class="form-control" placeholder="Paste link here" required>
                    </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="add_btn" class="btn btn-primary btn-add w-100 shadow-sm">
                        <i class="fas fa-save me-2"></i>Add Item
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card main-card p-4">
        <h5 class="fw-bold mb-4"><i class="fas fa-list me-2"></i>Product List</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($products)): ?>
                    <tr>
                        <td><img src="<?php echo $row['image']; ?>" onerror="this.src='https://via.placeholder.com/50';"></td>
                        <td class="fw-bold"><?php echo $row['name']; ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo $row['category']; ?></span></td>
                        <td class="text-primary fw-bold"><?php echo number_format($row['price']); ?> Ks</td>
                        <td class="text-center">
                            <a href="manage_products.php?del_id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-outline-danger px-3 rounded-pill"
                               onclick="return confirm('ဖျက်မှာ သေချာလား?')">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https