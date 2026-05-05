<?php
require_once('file/auth_admin.php');

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM product WHERE id='$id'");
    echo '<script>alert("تم حذف المنتج بنجاح"); window.location.href="out_of_stock.php";</script>';
    exit();
}

$count_available_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM product WHERE quantity > 0");
$count_available = mysqli_fetch_assoc($count_available_q)['total'];

$count_out_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM product WHERE quantity = 0");
$count_out = mysqli_fetch_assoc($count_out_q)['total'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنتجات الغير متوفرة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
body{font-family:Tahoma,Arial;background:#f5f5f5;margin:0;padding:20px}
.container{max-width:1200px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1)}
.header-stats{display:flex;gap:15px;margin-bottom:20px;justify-content:center;flex-wrap:wrap}
.stat-box{padding:15px 30px;border-radius:8px;font-size:18px;font-weight:bold;color:#fff;box-shadow:0 3px 8px rgba(0,0,0,0.2)}
.stat-available{background:linear-gradient(135deg,#ffa726 0%,#fb8c00 100%)}
.stat-out{background:linear-gradient(135deg,#ef5350 0%,#e53935 100%)}
.stat-out a{color:#fff;text-decoration:none}
h1{text-align:center;color:#333;margin-bottom:25px;font-size:26px}
.btn-add{display:inline-block;background:#4caf50;color:#fff;padding:12px 25px;border-radius:6px;text-decoration:none;margin-bottom:20px;font-weight:bold}
.btn-add:hover{background:#45a049}
.btn-back{display:inline-block;background:#2196F3;color:#fff;padding:12px 25px;border-radius:6px;text-decoration:none;margin-bottom:20px;font-weight:bold;margin-right:10px}
.btn-back:hover{background:#0b7dda}
table{width:100%;border-collapse:collapse;background:#fff}
thead{background:#f8f9fa}
th{padding:15px;text-align:center;border-bottom:2px solid #dee2e6;color:#333;font-size:14px;font-weight:bold}
td{padding:12px;text-align:center;border-bottom:1px solid #eee;font-size:13px}
td img{width:60px;height:60px;object-fit:cover;border-radius:5px;border:1px solid #ddd}
.badge-out{background:#f44336;color:#fff;padding:5px 12px;border-radius:15px;font-size:12px;font-weight:bold}
.btn-edit{background:#ff9800;color:#fff;padding:6px 14px;border-radius:5px;text-decoration:none;font-size:12px;display:inline-block}
.btn-edit:hover{background:#e68900}
.btn-delete{background:#f44336;color:#fff;padding:6px 14px;border-radius:5px;text-decoration:none;font-size:12px;display:inline-block;margin-right:5px}
.btn-delete:hover{background:#da190b}
.no-products{text-align:center;padding:60px 20px;background:#fff3cd;border:2px solid #ffc107;border-radius:10px;font-size:20px;color:#856404;margin:40px auto}
.no-products i{font-size:50px;display:block;margin-bottom:15px;color:#ffc107}
@media(max-width:768px){
    table{font-size:11px;display:block;overflow-x:auto;}
    th,td{padding:8px 4px}
    td img{width:40px;height:40px}
    .btn-edit,.btn-delete{padding:4px 8px;font-size:10px}
    .header-stats{flex-direction:column;}
    .stat-box{width:100%;box-sizing:border-box;}
}
</style>
<body>
<div class="container">
    <h1><i class="fa-solid fa-box-open"></i> إدارة المنتجات الغير متوفرة</h1>
    
    <div class="header-stats">
        <div class="stat-box stat-available">
            إجمالي المنتجات المتوفرة: <?php echo $count_available; ?>
        </div>
        <div class="stat-box stat-out">
            <a href="product.php">المنتجات الغير متوفرة: <?php echo $count_out; ?> | إضغط هنا</a>
        </div>
    </div>

    <a href="addproduct.php" class="btn-add"><i class="fa-solid fa-plus"></i> إضافة منتج جديد</a>
    <a href="product.php" class="btn-back"><i class="fa-solid fa-arrow-right"></i> كل المنتجات</a>

    <?php
    $query = "SELECT * FROM product WHERE quantity = 0 ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0){
    ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>صورة المنتج</th>
                <th>عنوان المنتج</th>
                <th>سعر المنتج</th>
                <th>الاحجام المتوفرة</th>
                <th>توفر المنتج</th>
                <th>الكمية المتوفرة</th>
                <th>الاقسام</th>
                <th>تفاصيل المنتج</th>
                <th>تعديل المنتج</th>
                <th>حذف</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            while($row = mysqli_fetch_assoc($result)){ 
            ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><img src="../uploads/img/<?php echo $row['proimg']; ?>" alt=""></td>
                <td><strong><?php echo htmlspecialchars($row['proname']); ?></strong></td>
                <td><?php echo number_format($row['proprice'], 2); ?> ريال</td>
                <td><?php echo htmlspecialchars($row['prosize']); ?></td>
                <td><span class="badge-out">غير متوفر</span></td>
                <td><strong style="color:#f44336;"><?php echo $row['quantity']; ?></strong></td>
                <td><?php echo htmlspecialchars($row['prosection']); ?></td>
                <td><?php echo mb_substr(htmlspecialchars($row['prodescrip']), 0, 30); ?>...</td>
                <td>
                    <a href="update.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                        <i class="fa-solid fa-pen"></i> تعديل
                    </a>
                </td>
                <td>
                    <a href="out_of_stock.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                        <i class="fa-solid fa-trash"></i> حذف
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php 
    } else {
        echo '<div class="no-products"><i class="fa-solid fa-check-circle"></i> لا توجد منتجات غير متوفرة حالياً - كل المنتجات متوفرة</div>';
    }
    ?>
</div>
</body>
</html>