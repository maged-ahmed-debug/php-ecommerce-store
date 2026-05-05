<?php
require_once('file/auth_admin.php');

if(isset($_POST['mark_delivered'])){
    $order_id = intval($_POST['order_id']);
    $has_prize = intval($_POST['has_prize']);
    
    $order_q = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id'");
    $order = mysqli_fetch_assoc($order_q);
    
    if($order){
        mysqli_begin_transaction($conn);
        try {
            $insert_sale = "INSERT INTO sales(order_id, user_id, full_name, phone, email, country, address, total_price, order_date, has_prize, delivery_phone) 
                            VALUES('{$order['id']}', '{$order['user_id']}', '".mysqli_real_escape_string($conn,$order['full_name'])."', 
                            '".mysqli_real_escape_string($conn,$order['phone'])."', '".mysqli_real_escape_string($conn,$order['email'])."', 
                            '".mysqli_real_escape_string($conn,$order['country'])."', '".mysqli_real_escape_string($conn,$order['address'])."', 
                            '{$order['total_price']}', '{$order['order_date']}', '$has_prize', '".mysqli_real_escape_string($conn,$order['delivery_phone'])."')";
            mysqli_query($conn, $insert_sale);
            $sale_id = mysqli_insert_id($conn);
            
            $items_q = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id='$order_id'");
            while($item = mysqli_fetch_assoc($items_q)){
                $insert_item = "INSERT INTO sales_items(sale_id, order_id, product_id, product_name, product_price, product_img, quantity) 
                                VALUES('$sale_id', '$order_id', '{$item['product_id']}', '".mysqli_real_escape_string($conn,$item['product_name'])."', 
                                '{$item['product_price']}', '".mysqli_real_escape_string($conn,$item['product_img'])."', '{$item['quantity']}')";
                mysqli_query($conn, $insert_item);
            }
            
            mysqli_query($conn, "DELETE FROM order_items WHERE order_id='$order_id'");
            mysqli_query($conn, "DELETE FROM orders WHERE id='$order_id'");
            
            mysqli_commit($conn);
            if($has_prize == 1){
                echo '<script>alert("تم تسليم الطلب مع الجائزة المستحقة بنجاح 🎉"); window.location.href="admin_orders.php";</script>';
            } else {
                echo '<script>alert("تم تسليم الطلب ونقله للمبيعات بنجاح"); window.location.href="admin_orders.php";</script>';
            }
            exit();
        } catch(Exception $e){
            mysqli_rollback($conn);
            echo '<script>alert("حدث خطأ أثناء التسليم"); window.location.href="admin_orders.php";</script>';
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات الزبائن</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container{width:95%;margin:20px auto;font-family:Tahoma;}
        .page-title{text-align:center;font-size:26px;font-weight:bold;color:#fff;margin-bottom:25px;padding:15px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:8px;}
        .prize-alert{
            background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);
            color:#fff;
            padding:15px 20px;
            border-radius:0;
            margin:0;
            box-shadow:0 4px 15px rgba(245,87,108,0.4);
            display:flex;
            align-items:center;
            gap:15px;
            animation:glow 2s infinite;
            border-bottom:3px solid #fff;
        }
        @keyframes glow{
            0%,100%{box-shadow:inset 0 0 20px rgba(255,255,255,0.3);}
            50%{box-shadow:inset 0 0 30px rgba(255,255,255,0.5);}
        }
        .prize-alert i{
            font-size:35px;
            animation:bounce 1s infinite;
        }
        @keyframes bounce{
            0%,100%{transform:translateY(0);}
            50%{transform:translateY(-8px);}
        }
        .prize-alert .text{
            font-size:17px;
            font-weight:bold;
            flex:1;
        }
        .prize-alert .text .highlight{
            background:#fff;
            color:#f5576c;
            padding:2px 8px;
            border-radius:5px;
            margin:0 5px;
        }
        .order-separator{
            height:8px;
            background:#e74c3c;
            margin:30px 0;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(231,76,60,0.4);
        }
        .order-box{background:#fff;border:3px solid #e0e0e0;border-radius:10px;margin-bottom:0;box-shadow:0 5px 15px rgba(0,0,0,0.15);overflow:hidden;}
        .order-header{background:#2c3e50;color:#fff;padding:12px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;font-size:14px;}
        .order-header strong{color:#f1c40f;}
        .status-badge{padding:4px 12px;border-radius:20px;font-size:13px;font-weight:bold;}
        .status-0{background:#ff9800;color:#fff;}
        .status-1{background:#2196F3;color:#fff;}
        .status-2{background:#4CAF50;color:#fff;}
        .status-3{background:#f44336;color:#fff;}
        .customer-info{padding:15px 20px;background:#ecf0f1;border-bottom:2px solid #bdc3c7;display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:8px;font-size:14px;}
        .items-table{width:100%;border-collapse:collapse;}
        .items-table thead{background:#34495e;color:#fff;}
        .items-table th{padding:10px;text-align:center;font-size:13px;}
        .items-table td{padding:10px;text-align:center;border-bottom:1px solid #ecf0f1;font-size:14px;}
        .items-table img{width:50px;height:50px;object-fit:cover;border-radius:4px;border:1px solid #ddd;}
        .product-id-badge{
            background:#3498db;
            color:#fff;
            padding:4px 10px;
            border-radius:15px;
            font-size:12px;
            font-weight:bold;
            display:inline-block;
        }
        .order-footer{background:#34495e;padding:12px 20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;}
        .order-total{font-size:18px;font-weight:bold;color:#f1c40f;}
        .btn-delivered{padding:8px 20px;background:#27ae60;color:#fff;border:none;border-radius:5px;cursor:pointer;font-weight:bold;font-size:15px;}
        .btn-delivered:hover{background:#229954;}
        .btn-delivered-prize{
            background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);
            animation:glow 2s infinite;
        }
        .btn-delivered-prize:hover{
            background:linear-gradient(135deg,#e881f5 0%,#e74c5a 100%);
        }
        .no-orders{text-align:center;padding:50px 20px;background:#fff;border:2px dashed #bdc3c7;border-radius:10px;font-size:18px;color:#7f8c8d;}
        .payment-paid{color:#27ae60;font-weight:bold;}
        .payment-pending{color:#e67e22;font-weight:bold;}
        @media(max-width:768px){
            .order-header{grid-template-columns:1fr;font-size:12px;}
            .customer-info{grid-template-columns:1fr;font-size:13px;}
            .items-table{display:block;overflow-x:auto;}
            .items-table table{min-width:700px;}
            .order-footer{flex-direction:column;text-align:center;}
        }
        @media(max-width:480px){
            .page-title{font-size:18px;padding:10px;}
            .prize-alert{flex-direction:column;text-align:center;}
            .prize-alert .text{font-size:14px;}
            th,td{padding:6px;font-size:11px;}
            .btn-delivered{width:100%;font-size:14px;}
        }
    </style>
</head>
<body>
<main>
<div class="admin-container">
    <div class="page-title">طلبات الزبائن - بانتظار التسليم</div>

    <?php
    $query_orders = "SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id ASC";
    $result_orders = mysqli_query($conn, $query_orders);
    $order_counter = 0;
    if(mysqli_num_rows($result_orders) > 0){
        while($order = mysqli_fetch_assoc($result_orders)){
            $order_counter++;
            $status_text = ['قيد المراجعة','تم الشحن','مكتمل','ملغي'][$order['status']];
            $status_class = 'status-' . $order['status'];
            $order_date = date('Y-m-d H:i', strtotime($order['order_date']));
            $user_id = $order['user_id'];
            
            $count_sales_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM sales WHERE user_id='$user_id'");
            $count_sales = mysqli_fetch_assoc($count_sales_q)['total'];
            
            $count_older_orders_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE user_id='$user_id' AND id < '{$order['id']}'");
            $count_older_orders = mysqli_fetch_assoc($count_older_orders_q)['total'];
            
            $order_number = $count_sales + $count_older_orders + 1;
            $count_completed = $count_sales;
            $has_prize = ($order_number % 5 == 0) ? 1 : 0;
            
            if($order_counter > 1){
                echo '<div class="order-separator"></div>';
            }
    ?>
    <div class="order-box">
        <?php if($has_prize == 1){ ?>
        <div class="prize-alert">
            <i class="fa-solid fa-trophy"></i>
            <div class="text">
                <i class="fa-solid fa-bell"></i> تنبيه: هذا الزبون <span class="highlight"><?php echo htmlspecialchars($order['full_name']); ?></span> 
                يستحق الجائزة! فهو أتم <span class="highlight"><?php echo $order_number; ?> طلبيات</span> 🎉
            </div>
        </div>
        <?php } ?>
        
        <div class="order-header">
            <span>رقم الطلب: <strong>#<?php echo $order['id']; ?></strong></span>
            <span>التاريخ: <strong><?php echo $order_date; ?></strong></span>
            <span>المستخدم: <strong><?php echo htmlspecialchars($order['username']); ?></strong></span>
            <span>الحالة: <strong class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></strong></span>
            <span>ترتيب الطلب: <strong style="color:#2ecc71;">الطلب رقم <?php echo $order_number; ?></strong></span>
        </div>

        <div class="customer-info">
            <div><strong>الاسم:</strong> <?php echo htmlspecialchars($order['full_name']); ?></div>
            <div><strong>الجوال:</strong> <?php echo htmlspecialchars($order['phone']); ?></div>
            <div><strong>الإيميل:</strong> <?php echo htmlspecialchars($order['email']); ?></div>
            <div><strong>الدولة:</strong> <?php echo htmlspecialchars($order['country']); ?></div>
            <div><strong>العنوان:</strong> <?php echo htmlspecialchars($order['address']); ?></div>
            <div><strong>طريقة الدفع:</strong> 
                <?php echo $order['payment_method'] == 'card' ? 'بطاقة ائتمانية' : 'الدفع عند الاستلام'; ?>
            </div>
            <div><strong>حالة الدفع:</strong> 
                <?php if($order['payment_status'] == 'paid'){ ?>
                    <span class="payment-paid">✓ تم الدفع - سند #<?php echo $order['id']; ?></span>
                <?php } else { ?>
                    <span class="payment-pending">بانتظار الدفع عند الاستلام</span>
                <?php } ?>
            </div>
            <div><strong>عدد طلباته المكتملة:</strong> 
                <span style="color:#3498db;font-weight:bold;"><?php echo $count_completed; ?> طلب</span>
            </div>
            <div><strong>إجمالي طلباته:</strong> 
                <span style="color:#e67e22;font-weight:bold;"><?php echo $order_number; ?> طلب</span>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>رقم المنتج</th>
                    <th>الصورة</th>
                    <th>المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $order_id = $order['id'];
                $query_items = "SELECT * FROM order_items WHERE order_id = '$order_id'";
                $result_items = mysqli_query($conn, $query_items);
                while($item = mysqli_fetch_assoc($result_items)){
                    $subtotal = $item['product_price'] * $item['quantity'];
                    $img_path = '../uploads/img/' . $item['product_img'];
                ?>
                <tr>
                    <td><span class="product-id-badge">#<?php echo $item['product_id']; ?></span></td>
                    <td>
                        <?php if(!empty($item['product_img']) && file_exists($img_path)){ ?>
                            <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        <?php } else { ?>
                            <span style="color:#999;font-size:12px;">لا توجد صورة</span>
                        <?php } ?>
                    </td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo number_format($item['product_price'], 2); ?> ريال</td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><strong><?php echo number_format($subtotal, 2); ?> ريال</strong></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="order-footer">
            <div class="order-total">الإجمالي: <?php echo number_format($order['total_price'], 2); ?> ريال</div>
            <form method="POST">
                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                <input type="hidden" name="has_prize" value="<?php echo $has_prize; ?>">
                <?php if($has_prize == 1){ ?>
                    <button type="submit" name="mark_delivered" class="btn-delivered btn-delivered-prize" onclick="return confirm('تأكيد تسليم الطلب مع الجائزة المستحقة؟')">
                        <i class="fa-solid fa-gift"></i> تم التسليم مع الجائزة
                    </button>
                <?php } else { ?>
                    <button type="submit" name="mark_delivered" class="btn-delivered" onclick="return confirm('تأكيد تسليم الطلب؟')">
                        <i class="fa-solid fa-check"></i> تم التسليم
                    </button>
                <?php } ?>
            </form>
        </div>
    </div>
    <?php
        }
    } else {
        echo '<div class="no-orders">لا توجد طلبات حالياً</div>';
    }
    ?>
</div>
</main>
</body>
</html>