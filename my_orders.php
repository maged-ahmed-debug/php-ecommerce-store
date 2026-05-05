<?php
session_start();
include('file/header.php');

if(!isset($_SESSION['user_id'])){
    echo '<script>alert("يجب تسجيل الدخول أولاً لعرض طلباتك"); window.location.href="user/login.php";</script>';
    exit();
}

$user_id = $_SESSION['user_id'];

// نحسب عدد الطلبات للزبون
$count_orders_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE user_id='$user_id'");
$count_orders = mysqli_fetch_assoc($count_orders_q)['total'];
$remaining = 5 - $count_orders;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .orders-container{width:90%;margin:30px auto;font-family:'Tahoma',Arial;}
        .page-title{text-align:center;font-size:28px;font-weight:bold;color:#333;margin-bottom:20px;padding:15px;background:#f8f9fa;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        
        /* صندوق الإشعار والجائزة */
        .reward-box{
            background: linear-gradient(135deg,#f093fb 0%,#f5576c 100%);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(245,87,108,0.3);
            text-align: center;
            animation: pulse 2s infinite;
        }
        @keyframes pulse{
            0%,100%{transform:scale(1);}
            50%{transform:scale(1.02);}
        }
        .reward-box i{
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }
        .reward-box .count{
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }
        .reward-box .text{
            font-size: 18px;
            margin: 5px 0;
        }
        .reward-box .progress{
            background: rgba(255,255,255,0.3);
            height: 15px;
            border-radius: 10px;
            margin: 15px 0;
            overflow: hidden;
        }
        .reward-box .progress-bar{
            background: #fff;
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        .reward-box .btn-claim{
            background: #fff;
            color: #f5576c;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
        }
        .reward-box .btn-claim:hover{
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .reward-box.won{
            background: linear-gradient(135deg,#11998e 0%,#38ef7d 100%);
            animation: none;
        }
        
        /* اشعار الدفع */
        .payment-notice{
            background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);
            color:#fff;
            padding:15px 20px;
            border-radius:10px;
            margin-bottom:15px;
            box-shadow:0 4px 15px rgba(56,239,125,0.3);
            text-align:center;
            font-size:16px;
            font-weight:bold;
        }
        .payment-notice i{
            font-size:22px;
            margin-left:8px;
            vertical-align:middle;
        }
        .payment-notice.waiting{
            background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);
            box-shadow:0 4px 15px rgba(245,87,108,0.3);
        }
        
        .order-box{background:#fff;border:2px solid #e0e0e0;border-radius:10px;margin-bottom:25px;box-shadow:0 3px 10px rgba(0,0,0,0.1);overflow:hidden;}
        .order-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:15px 20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;}
        .order-header span{font-size:15px;}
        .order-header strong{color:#ffe082;}
        .status-0{background:#ff9800;padding:3px 10px;border-radius:15px;}
        .status-1{background:#2196F3;padding:3px 10px;border-radius:15px;}
        .status-2{background:#4CAF50;padding:3px 10px;border-radius:15px;}
        .status-3{background:#f44336;padding:3px 10px;border-radius:15px;}
        .order-info{padding:15px 20px;background:#f9f9f9;border-bottom:1px solid #e0e0e0;line-height:1.8;}
        .order-info div{margin:5px 0;color:#555;}
        .order-info strong{color:#333;margin-left:5px;}
        .payment-details{background:#e8f5e9;padding:12px;border-radius:8px;margin-top:10px;border:2px solid #4caf50;}
        .payment-details.waiting{background:#fff3cd;border-color:#ffc107;color:#856404;}
        .payment-details strong{color:#2e7d32;}
        .payment-details.waiting strong{color:#856404;}
        .btn-receipt{display:inline-block;margin-top:8px;background:#4caf50;color:#fff;padding:8px 16px;border-radius:6px;text-decoration:none;transition:all 0.3s;}
        .btn-receipt:hover{background:#45a049;transform:translateY(-2px);}
        .items-table{width:100%;border-collapse:collapse;}
        .items-table thead{background:#f5f5f5;}
        .items-table th{padding:12px;text-align:center;border-bottom:2px solid #ddd;color:#333;font-size:14px;}
        .items-table td{padding:12px;text-align:center;border-bottom:1px solid #eee;}
        .items-table img{width:60px;height:60px;object-fit:cover;border-radius:5px;border:1px solid #ddd;}
        .items-table .product-name{text-align:right;font-weight:bold;color:#333;}
        .order-total{background:#f8f9fa;padding:15px 20px;text-align:left;font-size:18px;font-weight:bold;color:#2e7d32;border-top:2px solid #e0e0e0;}
        .no-orders{text-align:center;padding:60px 20px;background:#fff3cd;border:2px solid #ffc107;border-radius:10px;font-size:20px;color:#856404;margin:40px auto;width:70%;}
        .no-orders i{font-size:50px;display:block;margin-bottom:15px;color:#ffc107;}
        @media(max-width:768px){.orders-container{width:95%;}.order-header{flex-direction:column;}.items-table{font-size:12px;}.items-table img{width:40px;height:40px;}}
    </style>
</head>
<body>
<main>
<div class="orders-container">
    <div class="page-title">طلباتي</div>

    <!-- صندوق الجائزة والعداد -->
    <div class="reward-box <?php if($count_orders >= 5) echo 'won'; ?>">
        <?php if($count_orders >= 5){ ?>
            <i class="fa-solid fa-trophy"></i>
            <div class="text">مبروك! أكملت 5 طلبات</div>
            <div class="count"><?php echo $count_orders; ?> طلبات</div>
            <div class="text">لقد فزت بجائزة! 🎉</div>
            <button class="btn-claim" onclick="alert('تواصل مع الدعم للحصول على جائزتك: كوبون خصم 20%')">
                <i class="fa-solid fa-gift"></i> استلم جائزتك
            </button>
        <?php } else { ?>
            <i class="fa-solid fa-gift"></i>
            <div class="text">اجمع 5 طلبات واحصل على جائزة!</div>
            <div class="count"><?php echo $count_orders; ?> / 5 طلبات</div>
            <div class="progress">
                <div class="progress-bar" style="width:<?php echo ($count_orders/5)*100; ?>%"></div>
            </div>
            <div class="text">باقي لك <?php echo $remaining; ?> طلبات فقط</div>
        <?php } ?>
    </div>

    <?php
    $query_orders = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC";
    $result_orders = mysqli_query($conn, $query_orders);
    if(mysqli_num_rows($result_orders) > 0){
        while($order = mysqli_fetch_assoc($result_orders)){
            $status_text = ''; $status_class = 'status-' . $order['status'];
            if($order['status'] == 0) $status_text = 'قيد المراجعة';
            elseif($order['status'] == 1) $status_text = 'تم الشحن';
            elseif($order['status'] == 2) $status_text = 'مكتمل';
            elseif($order['status'] == 3) $status_text = 'ملغي';
            else $status_text = 'غير معروف';
            $order_date = date('Y-m-d H:i', strtotime($order['order_date']));
    ?>
    
    <!-- اشعار الدفع فوق الطلبية -->
    <?php if($order['payment_method'] == 'card' && !empty($order['payment_receipt'])){ ?>
   
    <?php } elseif($order['payment_method'] == 'card' && empty($order['payment_receipt'])){ ?>
    <div class="payment-notice waiting">
        <i class="fa-solid fa-hourglass-half"></i>
        طريقة الدفع: بطاقة - بانتظار رفع السند البنكي
    </div>
    <?php } ?>
    
    <div class="order-box">
        <div class="order-header">
            <span>رقم الطلب: <strong>#<?php echo $order['id']; ?></strong></span>
            <span>تاريخ الطلب: <strong><?php echo $order_date; ?></strong></span>
            <span>الحالة: <strong class="<?php echo $status_class; ?>"><?php echo $status_text; ?></strong></span>
        </div>
        <div class="order-info">
            <div><strong>الاسم:</strong> <?php echo htmlspecialchars($order['full_name']); ?></div>
            <div><strong>الجوال:</strong> <?php echo htmlspecialchars($order['phone']); ?></div>
            <div><strong>البريد:</strong> <?php echo htmlspecialchars($order['email']); ?></div>
            <div><strong>الدولة:</strong> <?php echo htmlspecialchars($order['country']); ?></div>
            <div><strong>العنوان:</strong> <?php echo htmlspecialchars($order['address']); ?></div>
            <div><strong>طريقة الدفع:</strong> 
                <?php 
                if($order['payment_method'] == 'card'){
                    echo '<span style="color:#667eea;font-weight:bold;"><i class="fa-solid fa-credit-card"></i> الدفع بالبطاقة</span>';
                } else {
                    echo '<span style="color:#28a745;font-weight:bold;"><i class="fa-solid fa-money-bill"></i> الدفع عند الاستلام</span>';
                }
                ?>
            </div>
            
            <?php if($order['payment_method'] == 'card' && !empty($order['payment_receipt'])){ ?>
            <div class="payment-details">
                <strong><i class="fa-solid fa-check-circle"></i> تم السداد عبر سند بنكي</strong><br>
                <div style="margin-top:8px;">
                    <strong>رقم السند:</strong> <?php echo htmlspecialchars($order['payment_receipt']); ?><br>
                    <a href="uploads/receipts/<?php echo $order['payment_receipt']; ?>" target="_blank" class="btn-receipt">
                        <i class="fa-solid fa-file-image"></i> عرض صورة السند
                    </a>
                </div>
            </div>
            <?php } ?>
            
            <?php if($order['payment_method'] == 'card' && empty($order['payment_receipt'])){ ?>
            <div class="payment-details waiting">
                <strong><i class="fa-solid fa-hourglass-half"></i> بانتظار رفع السند البنكي</strong>
            </div>
            <?php } ?>
        </div>
        <table class="items-table">
            <thead><tr><th>الصورة</th><th>اسم المنتج</th><th>السعر</th><th>الكمية</th><th>الإجمالي</th></tr></thead>
            <tbody>
                <?php
                $order_id = $order['id'];
                $query_items = "SELECT * FROM order_items WHERE order_id = '$order_id'";
                $result_items = mysqli_query($conn, $query_items);
                while($item = mysqli_fetch_assoc($result_items)){
                    $subtotal = $item['product_price'] * $item['quantity'];
                ?>
                <tr>
                    <td><img src="uploads/img/<?php echo $item['product_img']; ?>" alt=""></td>
                    <td class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo number_format($item['product_price'], 2); ?> ريال</td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><strong><?php echo number_format($subtotal, 2); ?> ريال</strong></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="order-total">المبلغ الإجمالي: <?php echo number_format($order['total_price'], 2); ?> ريال</div>
    </div>
    <?php
        }
    } else {
        echo '<div class="no-orders"><i class="fa-solid fa-box-open"></i>لا توجد طلبات حتى الآن</div>';
    }
    ?>
</div>
</main>
</body>
</html>