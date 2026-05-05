<?php
session_start();
include('include/connected.php');

if(!isset($_SESSION['user_id']) || !isset($_SESSION['pending_order'])){
    header("Location: checkout.php");
    exit();
}

$order_data = $_SESSION['pending_order'];
$success_msg = '';
$error_msg = '';

if(isset($_POST['upload_receipt'])){
    if(isset($_FILES['receipt_img']) && $_FILES['receipt_img']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $filename = $_FILES['receipt_img']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)){
            $new_name = 'PAY-' . date('YmdHis') . '-' . rand(1000,9999) . '.' . $ext;
            $upload_path = 'uploads/receipts/' . $new_name;
            
            if(!is_dir('uploads/receipts/')){
                mkdir('uploads/receipts/', 0777, true);
            }
            
            if(move_uploaded_file($_FILES['receipt_img']['tmp_name'], $upload_path)){
                $_SESSION['payment_receipt'] = $new_name;
                $success_msg = 'تم رفع السند بنجاح! رقم السند: ' . $new_name;
            } else {
                $error_msg = 'فشل رفع الملف';
            }
        } else {
            $error_msg = 'صيغة الملف غير مسموحة. المسموح: jpg, png, pdf';
        }
    } else {
        $error_msg = 'يرجى اختيار صورة السند';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدفع بالبطاقة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
body{font-family:Arial,Helvetica,sans-serif;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;margin:0}
.container{max-width:500px;width:100%;background:#fff;padding:30px;border-radius:16px;box-shadow:0 15px 35px rgba(0,0,0,0.2)}
h1{text-align:center;color:#2c3e50;margin-bottom:20px;font-size:24px}
.info-box{background:#f8f9fa;padding:15px;border-radius:8px;margin-bottom:20px;border:2px solid #667eea}
.info-box p{margin:5px 0;font-size:15px}
.info-box strong{color:#667eea}
.total{font-size:22px;font-weight:bold;color:#27ae60;text-align:center;margin:15px 0}
.input-group{margin-bottom:15px}
label{font-size:16px;font-weight:bold;display:block;margin-bottom:8px;color:#2c3e50}
input[type="file"]{width:100%;padding:10px;border:2px dashed #ccc;border-radius:8px;font-size:14px;box-sizing:border-box;cursor:pointer}
button{width:100%;padding:14px;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);font-size:16px;font-weight:bold;border-radius:8px;cursor:pointer;color:#fff;border:none;margin-top:10px}
button:hover{opacity:0.9}
.btn-back{width:100%;padding:12px;background:#28a745;font-size:16px;font-weight:bold;border-radius:8px;cursor:pointer;color:#fff;border:none;margin-top:10px;text-decoration:none;display:block;text-align:center}
.btn-back:hover{background:#218838}
.error-box{background:#f8d7da;color:#721c24;padding:12px;border-radius:6px;margin-bottom:15px;text-align:center;border:1px solid #f5c6cb}
.success-box{background:#d4edda;color:#155724;padding:12px;border-radius:6px;margin-bottom:15px;text-align:center;border:1px solid #c3e6cb;font-weight:bold}
.note{background:#fff3cd;color:#856404;padding:12px;border-radius:6px;margin-bottom:15px;font-size:14px;border:1px solid #ffeaa7}
</style>
<body>
    <div class="container">
        <h1><i class="fa-solid fa-credit-card"></i> إتمام السداد</h1>
        
        <?php if($error_msg){ ?>
            <div class="error-box"><?php echo $error_msg; ?></div>
        <?php } ?>
        
        <?php if($success_msg){ ?>
            <div class="success-box"><?php echo $success_msg; ?></div>
        <?php } ?>
        
        <div class="info-box">
            <p><strong>الاسم:</strong> <?php echo htmlspecialchars($order_data['full_name']); ?></p>
            <p><strong>الجوال:</strong> <?php echo htmlspecialchars($order_data['phone']); ?></p>
            <p><strong>العنوان:</strong> <?php echo htmlspecialchars($order_data['address']); ?></p>
        </div>
        
        <div class="total">
            المبلغ المطلوب: <?php echo number_format($order_data['total_price'], 2); ?> ريال
        </div>
        
        <div class="note">
            <i class="fa-solid fa-info-circle"></i> قم بتحويل المبلغ ثم ارفع صورة السند البنكي
        </div>
        
        <?php if(!isset($_SESSION['payment_receipt'])){ ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>رفع صورة السند البنكي</label>
                <input type="file" name="receipt_img" accept="image/*,.pdf" required>
            </div>
            <button type="submit" name="upload_receipt">
                <i class="fa-solid fa-upload"></i> رفع السند
            </button>
        </form>
        <?php } else { ?>
        <a href="checkout.php" class="btn-back">
            <i class="fa-solid fa-arrow-right"></i> العودة لإتمام الطلب
        </a>
        <?php } ?>
    </div>
</body>
</html>