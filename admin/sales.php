<?php
require_once('file/auth_admin.php');

// نحسب عدد المبيعات
$count_sales_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM sales");
$count_sales = mysqli_fetch_assoc($count_sales_q)['total'];

// نحسب إجمالي المبلغ
$total_revenue_q = mysqli_query($conn, "SELECT SUM(total_price) as total FROM sales");
$total_revenue = mysqli_fetch_assoc($total_revenue_q)['total'];
$total_revenue = $total_revenue ? $total_revenue : 0;

// إرجاع البضاعة
if(isset($_GET['return_sale']) && !empty($_GET['return_sale'])){
    $return_id = intval($_GET['return_sale']);
    $items_q = mysqli_query($conn, "SELECT * FROM sales_items WHERE sale_id = '$return_id'");
    
    if(mysqli_num_rows($items_q) > 0){
        mysqli_begin_transaction($conn);
        try {
            while($item = mysqli_fetch_assoc($items_q)){
                mysqli_query($conn, "UPDATE product SET quantity = quantity + {$item['quantity']} WHERE id = '{$item['product_id']}'");
            }
            mysqli_query($conn, "DELETE FROM sales_items WHERE sale_id = '$return_id'");
            mysqli_query($conn, "DELETE FROM sales WHERE id = '$return_id'");
            mysqli_commit($conn);
            echo '<script>alert("تم إرجاع البضاعة بنجاح وإضافتها للمخزون"); window.location.href="sales.php";</script>';
            exit();
        } catch(Exception $e){
            mysqli_rollback($conn);
            echo '<script>alert("حدث خطأ أثناء الإرجاع"); window.location.href="sales.php";</script>';
            exit();
        }
    } else {
        echo '<script>alert("المبيعة غير موجودة"); window.location.href="sales.php";</script>';
        exit();
    }
}

// البحث برقم المبيعة
if(isset($_GET['search_sale']) && !empty($_GET['search_sale'])){
    $search_id = intval($_GET['search_sale']);
    $query_sales = "SELECT * FROM sales WHERE id = '$search_id' ORDER BY id DESC";
} else {
    $query_sales = "SELECT * FROM sales ORDER BY id DESC";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المبيعات المكتملة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sales-container{width:95%;margin:20px auto;font-family:Tahoma;}
        .header-wrapper{position:relative;margin-bottom:25px;}
        .page-title{text-align:center;font-size:26px;font-weight:bold;color:#fff;padding:15px;background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);border-radius:8px;}
        .stat-btn{position:absolute;top:50%;transform:translateY(-50%);padding:12px 25px;border-radius:8px;font-size:16px;font-weight:bold;color:#fff;box-shadow:0 3px 10px rgba(0,0,0,0.2);display:flex;align-items:center;gap:8px;}
        .stat-btn-right{right:10px;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);}
        .stat-btn-left{left:10px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);}
        .stat-btn i{font-size:20px;}
        .stat-btn .num{font-size:20px;}
        .search-return-box{
            background:#fff;
            padding:20px;
            border-radius:10px;
            margin-bottom:20px;
            box-shadow:0 3px 10px rgba(0,0,0,0.1);
            border:2px solid #667eea;
        }
        .search-return-box form{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            justify-content:center;
            align-items:center;
        }
        .search-return-box input{
            padding:12px 15px;
            border:2px solid #667eea;
            border-radius:8px;
            font-size:14px;
            width:250px;
            box-sizing:border-box;
        }
        .btn-search{
            background:#667eea;
            color:#fff;
            padding:12px 25px;
            border:none;
            border-radius:8px;
            cursor:pointer;
            font-weight:bold;
            font-size:14px;
        }
        .btn-search:hover{background:#5568d3;}
        .btn-return{
            background:#e74c3c;
            color:#fff;
            padding:12px 25px;
            border-radius:8px;
            text-decoration:none;
            font-weight:bold;
            font-size:14px;
            display:inline-block;
        }
        .btn-return:hover{background:#c0392b;}
        .btn-clear{
            background:#95a5a6;
            color:#fff;
            padding:12px 25px;
            border-radius:8px;
            text-decoration:none;
            font-weight:bold;
            font-size:14px;
        }
        .btn-clear:hover{background:#7f8c8d;}
        .sale-box{background:#fff;border:2px solid #e0e0e0;border-radius:10px;margin-bottom:20px;box-shadow:0 3px 10px rgba(0,0,0,0.1);overflow:hidden;}
        .sale-header{background:#2c3e50;color:#fff;padding:12px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;font-size:14px;}
        .sale-header strong{color:#f1c40f;}
        .customer-info{padding:15px 20px;background:#ecf0f1;border-bottom:2px solid #bdc3c7;display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:8px;font-size:14px;}
        .items-table{width:100%;border-collapse:collapse;}
        .items-table thead{background:#34495e;color:#fff;}
        .items-table th{padding:10px;text-align:center;font-size:13px;}
        .items-table td{padding:10px;text-align:center;border-bottom:1px solid #ecf0f1;font-size:14px;}
        .items-table img{width:50px;height:50px;object-fit:cover;border-radius:4px;}
        .sale-footer{background:#16a085;padding:12px 20px;color:#fff;font-size:16px;font-weight:bold;}
        @media(max-width:768px){
            .stat-btn{position:static;transform:none;margin:10px auto;display:inline-flex;width:45%;}
            .header-wrapper{text-align:center;}
            .stat-btn-left{margin-left:2%;}
            .stat-btn-right{margin-right:2%;}
            .search-return-box form{flex-direction:column;}
            .search-return-box input{width:100%;}
            .sale-header{grid-template-columns:1fr;font-size:12px;}
            .customer-info{grid-template-columns:1fr;font-size:13px;}
            .items-table{display:block;overflow-x:auto;}
            .items-table table{min-width:600px;}
        }
        @media(max-width:480px){
            .page-title{font-size:18px;}
            .stat-btn{width:100%;margin:5px 0;font-size:14px;}
            .stat-btn .num{font-size:16px;}
            th,td{padding:6px;font-size:11px;}
        }
    </style>
</head>
<body>
<main>
<div class="sales-container">
    <div class="header-wrapper">
        <div class="stat-btn stat-btn-right">
            <i class="fa-solid fa-bag-shopping"></i>
            <div>
                <div>عدد المبيعات</div>
                <div class="num"><?php echo $count_sales; ?></div>
            </div>
        </div>
        
        <div class="page-title">المبيعات المكتملة</div>
        
        <div class="stat-btn stat-btn-left">
            <i class="fa-solid fa-sack-dollar"></i>
            <div>
                <div>إجمالي الأرباح</div>
                <div class="num"><?php echo number_format($total_revenue, 2); ?> ريال</div>
            </div>
        </div>
    </div>

    <div class="search-return-box">
        <form method="GET" action="">
            <input type="number" name="search_sale" placeholder="ابحث برقم المبيعة #..." 
                   value="<?php echo $_GET['search_sale'] ?? ''; ?>">
            <button type="submit" class="btn-search">
                <i class="fa-solid fa-search"></i> بحث
            </button>
            <?php if(isset($_GET['search_sale']) && !empty($_GET['search_sale'])){ ?>
            <a href="sales.php?return_sale=<?php echo $_GET['search_sale']; ?>" 
               onclick="return confirm('هل أنت متأكد من إرجاع هذه المبيعة؟ سيتم إرجاع الكميات للمخزون وحذف المبيعة نهائياً')" 
               class="btn-return">
                <i class="fa-solid fa-rotate-left"></i> إرجاع البضاعة
            </a>
            <a href="sales.php" class="btn-clear">
                <i class="fa-solid fa-xmark"></i> إلغاء البحث
            </a>
            <?php } ?>
        </form>
    </div>

    <?php
    $result_sales = mysqli_query($conn, $query_sales);
    if(mysqli_num_rows($result_sales) > 0){
        while($sale = mysqli_fetch_assoc($result_sales)){
            $delivered_date = date('Y-m-d H:i', strtotime($sale['delivered_date']));
    ?>
    <div class="sale-box">
        <div class="sale-header">
            <span>رقم المبيعة: <strong>#<?php echo $sale['id']; ?></strong></span>
            <span>رقم الطلب الأصلي: <strong>#<?php echo $sale['order_id']; ?></strong></span>
            <span>تاريخ التسليم: <strong><?php echo $delivered_date; ?></strong></span>
        </div>

        <div class="customer-info">
            <div><strong>الزبون:</strong> <?php echo htmlspecialchars($sale['full_name']); ?></div>
            <div><strong>الجوال:</strong> <?php echo htmlspecialchars($sale['phone']); ?></div>
            <div><strong>العنوان:</strong> <?php echo htmlspecialchars($sale['address']); ?></div>
            <div><strong>هاتف التوصيل:</strong> <?php echo htmlspecialchars($sale['delivery_phone']); ?></div>
            <div><strong>الدولة:</strong> <?php echo htmlspecialchars($sale['country']); ?></div>
            <?php if($sale['has_prize'] == 1){ ?>
            <div><strong style="color:#e74c3c;"><i class="fa-solid fa-gift"></i> تم تسليم جائزة</strong></div>
            <?php } ?>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sale_id = $sale['id'];
                $query_items = "SELECT * FROM sales_items WHERE sale_id = '$sale_id'";
                $result_items = mysqli_query($conn, $query_items);
                while($item = mysqli_fetch_assoc($result_items)){
                    $subtotal = $item['product_price'] * $item['quantity'];
                ?>
                <tr>
                    <td><img src="../uploads/img/<?php echo $item['product_img']; ?>"></td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo number_format($item['product_price'], 2); ?> ريال</td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><strong><?php echo number_format($subtotal, 2); ?> ريال</strong></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="sale-footer">
            المبلغ الإجمالي: <?php echo number_format($sale['total_price'], 2); ?> ريال
        </div>
    </div>
    <?php
        }
    } else {
        if(isset($_GET['search_sale'])){
            echo '<div style="text-align:center;padding:50px;background:#fff3cd;border:2px solid #ffc107;border-radius:10px;font-size:18px;color:#856404;"><i class="fa-solid fa-search" style="font-size:40px;display:block;margin-bottom:10px;"></i>لا توجد مبيعة بهذا الرقم</div>';
        } else {
            echo '<div style="text-align:center;padding:50px;">لا توجد مبيعات مكتملة</div>';
        }
    }
    ?>
</div>
</main>
</body>
</html>