<?php
session_start();
include('include/connected.php');

if(!isset($_SESSION['user_id'])){
    header("Location: user/login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$delivery_phone = "+966562458449";
$payment_done = isset($_SESSION['payment_receipt']) ? $_SESSION['payment_receipt'] : null;

if(isset($_POST['proceed'])){
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $country = mysqli_real_escape_string($conn, trim($_POST['country']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    $cart_q = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
    $total_price = 0;
    $cart_items = [];
    
    while($item = mysqli_fetch_assoc($cart_q)){
        $total_price += $item['price'] * $item['quantity'];
        $cart_items[] = $item;
    }
    
    if($total_price > 0){
        // لو اختار بطاقة + ما دفع = نوديه payment.php فوراً
        if($payment_method == 'card' && !$payment_done){
            $_SESSION['pending_order'] = [
                'full_name' => $full_name,
                'phone' => $phone,
                'email' => $email,
                'country' => $country,
                'address' => $address,
                'total_price' => $total_price,
                'cart_items' => $cart_items
            ];
            echo '<script>window.location.href="payment.php";</script>';
            exit();
        }
        
        // لو كاش أو دفع بالبطاقة خلاص = نحفظ الطلب
        $pay_status = ($payment_method == 'card' && $payment_done) ? 'paid' : 'pending';
        $receipt = ($payment_method == 'card' && $payment_done) ? $payment_done : null;
        
        $insert_order = "INSERT INTO orders(user_id, full_name, phone, email, country, address, total_price, payment_method, payment_status, payment_receipt, status, order_date) 
                         VALUES('$user_id', '$full_name', '$phone', '$email', '$country', '$address', '$total_price', '$payment_method', '$pay_status', '$receipt', 0, NOW())";
        
        if(mysqli_query($conn, $insert_order)){
            $order_id = mysqli_insert_id($conn);
            
            foreach($cart_items as $item){
                $insert_item = "INSERT INTO order_items(order_id, product_id, product_name, product_price, product_img, quantity) 
                                VALUES('$order_id', '{$item['product_id']}', '{$item['name']}', '{$item['price']}', '{$item['img']}', '{$item['quantity']}')";
                mysqli_query($conn, $insert_item);
                mysqli_query($conn, "UPDATE product SET quantity = quantity - {$item['quantity']} WHERE id = '{$item['product_id']}'");
            }
            
            mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
            unset($_SESSION['pending_order']);
            unset($_SESSION['payment_receipt']);
            
            echo '<script>alert("تم تأكيد طلبك بنجاح! رقم طلبك : # '.$order_id.'"); window.location.href="my_orders.php";</script>';
            exit();
        } else {
            $error_msg = "حدث خطأ: " . mysqli_error($conn);
        }
    } else {
        $error_msg = "السلة فارغة!";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام الشراء</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
body{font-family:Arial,Helvetica,sans-serif;background-color:azure;margin:0;padding:20px}
h1{text-align:center;color:black;margin-bottom:20px}
.container{max-width:900px;margin:auto;background-color:rgb(207,209,208);padding:20px;border-radius:10px;box-shadow:0 5px rgba(0,0,0,0.2)}
.display-order{display:flex;flex-wrap:wrap;gap:12px;padding:15px;background-color:rgb(255,197,122);border-radius:10px;justify-content:center}
.product-box{flex:0 1 100px;background-color:#fff;border-radius:10px;padding:10px;text-align:center;box-shadow:0 4px 10px rgba(0,0,0,0.08);transition:all 0.3s ease}
.product-box:hover{transform:translateY(-5px);box-shadow:0 8px 20px rgba(0,0,0,0.15)}
.product-box img{width:60px;height:50px;border-radius:5px;margin-bottom:5px}
.product-box p{font-size:12px;margin:2px;color:black;font-weight:bold}
.product-box i{color:#007bff;font-weight:bold}
.total-container{text-align:center;font-size:18px;font-weight:bold;margin:15px;color:#333}
h5{text-align:center;font-size:20px}
.input-group{margin-bottom:10px}
input,select{width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;font-size:14px;box-sizing:border-box}
label{font-size:16px;font-weight:bold;text-align:right;display:block;margin-bottom:5px}
button{width:100%;padding:12px;background-color:#0057b5;font-size:16px;font-weight:bold;border-radius:6px;cursor:pointer;color:#fff;border:none;margin-top:10px}
button:hover{background-color:#002d5d}
.btn-track{width:97.5%;padding:12px;background-color:#28a745;font-size:16px;font-weight:bold;border-radius:6px;cursor:pointer;color:#fff;border:none;margin-top:10px;text-decoration:none;display:block;text-align:center}
.btn-track:hover{background-color:#218838}
.btn-track i{margin-left:8px}
.payment-box{background:#fff;padding:15px;border-radius:8px;margin-bottom:15px;border:2px solid #0057b5}
.payment-box label{color:#0057b5}
.error-box{background:#f8d7da;color:#721c24;padding:12px;border-radius:6px;margin-bottom:15px;text-align:center;border:1px solid #f5c6cb}
.success-box{background:#d4edda;color:#155724;padding:12px;border-radius:6px;margin-bottom:15px;text-align:center;border:1px solid #c3e6cb;font-weight:bold}
</style>
<body>
    <h1>إتمام عملية الشراء</h1>
    
    <div class="container">
        <?php if(isset($error_msg)){ ?>
            <div class="error-box"><?php echo $error_msg; ?></div>
        <?php } ?>
        
        <?php if($payment_done){ ?>
            <div class="success-box">
                <i class="fa-solid fa-check-circle"></i> تم رفع السند بنجاح! رقم السند: <?php echo $payment_done; ?> - اضغط تأكيد الطلب الآن
            </div>
        <?php } ?>
        
        <div class="display-order">
            <?php
            $cart_q = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
            $total = 0;
            if(mysqli_num_rows($cart_q) > 0){
                while($row = mysqli_fetch_assoc($cart_q)){
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
            ?>
            <div class="product-box">
                <img src="uploads/img/<?php echo $row['img']; ?>" alt="">
                <p><?php echo $row['name']; ?></p>
                <p><i>الكمية: <?php echo $row['quantity']; ?></i></p>
                <p><i><?php echo number_format($subtotal, 2); ?> ريال</i></p>
            </div>
            <?php
                }
            } else {
                echo '<p>السلة فارغة</p>';
            }
            ?>
        </div>
        
        <div class="total-container">
            الإجمالي: <?php echo number_format($total, 2); ?> ريال
        </div>
        
        <h5>بيانات الشحن</h5>
        <form method="POST" action="">
            <div class="input-group">
                <label>الاسم الكامل</label>
                <input type="text" name="full_name" value="<?php echo $_SESSION['pending_order']['full_name'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>رقم الجوال</label>
                <input type="text" name="phone" value="<?php echo $_SESSION['pending_order']['phone'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="<?php echo $_SESSION['pending_order']['email'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>الدولة</label>
                <input type="text" name="country" value="<?php echo $_SESSION['pending_order']['country'] ?? 'السعودية'; ?>" required>
            </div>
            <div class="input-group">
                <label>العنوان بالتفصيل</label>
                <input type="text" name="address" value="<?php echo $_SESSION['pending_order']['address'] ?? ''; ?>" required>
            </div>
            
            <div class="payment-box">
                <label>طريقة الدفع</label>
                <select name="payment_method" id="payment_method" required <?php echo $payment_done ? 'disabled' : ''; ?>>
                    <option value="">-- اختر طريقة الدفع --</option>
                    <option value="cod" <?php echo (!$payment_done ? 'selected' : ''); ?>>الدفع عند الاستلام</option>
                    <option value="card" <?php echo ($payment_done ? 'selected' : ''); ?>>الدفع بالبطاقة</option>
                </select>
                <?php if($payment_done){ ?>
                    <input type="hidden" name="payment_method" value="card">
                <?php } ?>
            </div>
            
            <button type="submit" name="proceed" id="btn_submit">
                <i class="fa-solid fa-check"></i> <?php echo $payment_done ? 'تأكيد الطلب' : 'متابعة'; ?>
            </button>
        </form>
        
        <a href="tel:<?php echo $delivery_phone; ?>" class="btn-track">
            <i class="fa-solid fa-phone"></i> متابعة الطلب - <?php echo $delivery_phone; ?>
        </a>
    </div>

<script>
document.getElementById('payment_method').addEventListener('change', function(){
    let btn = document.getElementById('btn_submit');
    if(this.value == 'card'){
        btn.innerHTML = '<i class="fa-solid fa-credit-card"></i> الانتقال للدفع';
    } else {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> تأكيد الطلب';
    }
});
</script>
</body>
</html>