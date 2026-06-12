<?php
include 'db.php';
session_start();

// Admin Login စစ်ဆေးခြင်း
if(!isset($_SESSION['admin_logged_in'])){ 
    header("Location: admin_login.php"); 
    exit(); 
}

// ၁။ Review ဖျက်သည့်အပိုင်း
if(isset($_GET['delete_id'])){
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM reviews WHERE id='$id'");
    header("Location: view_reviews.php");
    exit();
}

// ၂။ Reply ပြန်သည့်အပိုင်း
if(isset($_POST['submit_reply'])){
    $rev_id = mysqli_real_escape_string($conn, $_POST['review_id']);
    $reply = mysqli_real_escape_string($conn, $_POST['reply_text']);
    // admin_reply အကန့်ထဲသို့ Update လုပ်ခြင်း
    mysqli_query($conn, "UPDATE reviews SET admin_reply='$reply' WHERE id='$rev_id'");
    header("Location: view_reviews.php");
    exit();
}

// ၃။ Query ကို JOIN သုံးပြီး နာမည်ပါဆွဲထုတ်ခြင်း (user_name error မတက်စေရန်)
$res = mysqli_query($conn, "SELECT reviews.*, users.username FROM reviews 
                            LEFT JOIN users ON reviews.user_id = users.id 
                            ORDER BY reviews.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customer Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-responsive { border-radius: 10px; overflow: hidden; }
        .admin-reply-box { border-left: 4px solid #0d6efd; background: #f8f9fa; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-comments text-primary me-2"></i>Customer Reviews</h2>
        <a href="admin_dashboard.php" class="btn btn-outline-dark rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Admin's Response</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $modals_html = ""; // Modal ကုဒ်များကို သိမ်းဆည်းရန်
                        while($row = mysqli_fetch_assoc($res)): 
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-dark"><?php echo htmlspecialchars($row['username'] ?? 'Guest User'); ?></td>
                            <td>
                                <?php for($i=1; $i<=5; $i++) {
                                    echo ($i <= $row['rating']) ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>';
                                } ?>
                            </td>
                            <td style="max-width: 300px;"><?php echo htmlspecialchars($row['comment']); ?></td>
                            <td>
                                <?php if(!empty($row['admin_reply'])): ?>
                                    <div class="admin-reply-box shadow-sm">
                                        <small class="text-primary fw-bold">Admin's Reply:</small><br>
                                        <small class="text-dark"><?php echo htmlspecialchars($row['admin_reply']); ?></small>
                                        </div>
                                <?php else: ?>
                                    <span class="text-muted small"><em>Waiting for response...</em></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#replyModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-reply me-1"></i> Reply
                                </button>
                                
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm px-3 shadow-sm ms-1" onclick="return confirm('ဖျက်မှာ သေချာလား?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <?php 
                        // Modal ကုဒ်များကို ဤနေရာတွင် စုစည်းထားမည်
                        $modals_html .= '
                        <div class="modal fade" id="replyModal'.$row['id'].'" tabindex="-1" aria-labelledby="modalLabel'.$row['id'].'" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="POST" class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalLabel'.$row['id'].'">Reply to '.htmlspecialchars($row['username'] ?? 'Guest').'</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="review_id" value="'.$row['id'].'">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small">CUSTOMER COMMENT</label>
                                            <div class="p-3 bg-light rounded border text-secondary">
                                                "'.htmlspecialchars($row['comment']).'"
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Your Response</label>
                                            <textarea name="reply_text" class="form-control" rows="4" placeholder="အကြောင်းပြန်စာ ရေးပေးပါ..." required>'.($row['admin_reply'] ?? '').'</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0">
                                        <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="submit_reply" class="btn btn-primary px-4 rounded-pill">Send Reply <i class="fas fa-paper-plane ms-1"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>';
                        endwhile; 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php echo $modals_html; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>