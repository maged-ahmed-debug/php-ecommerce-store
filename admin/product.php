<?php
require_once('file/auth_admin.php');

if(isset($_GET['id']) && isset($_GET['delete'])){
    $id = intval($_GET['id']); 
    $query = "DELETE FROM product WHERE id = $id";
    $delet = mysqli_query($conn, $query);
    
    if($delet){
        echo '<script>alert("تــم الحــذف بنجــاح"); window.location="product.php";</script>'; 
        exit();
    } else {
        echo '<script>alert("لــم يتــم الحــذف هنــاك خطــا مــا");</script>'; 
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحــة المنتجـــات</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @media(max-width:768px){
            .sidebar_container{overflow-x:auto;}
            table{min-width:1000px;}
            th,td{padding:6px;font-size:12px;}
            img{width:40px !important;height:40px !important;}
            button{padding:5px 8px !important;font-size:12px !important;}
        }
        @media(max-width:480px){
            div[style*="padding:15px"]{padding:10px 15px !important;font-size:16px !important;}
            div[style*="font-size:22px"]{font-size:18px !important;}
        }
    </style>
</head>
<body>

<?php
$count_query = "SELECT COUNT(*) as total FROM product WHERE quantity > 0";
$count_result = mysqli_query($conn, $count_query);
$total_products = mysqli_fetch_assoc($count_result)['total'];

$count_out_query = "SELECT COUNT(*) as total FROM product WHERE quantity = 0";
$count_out_result = mysqli_query($conn, $count_out_query);
$total_out_products = mysqli_fetch_assoc($count_out_result)['total'];
?>
<div style="text-align:center; margin:20px 0 30px 0;">
    <div style="display:inline-block; background:#f5aa2e; color:#000; padding:15px 40px; border-radius:10px; font-size:22px; font-weight:bold; box-shadow:0 4px 8px rgba(0,0,0,0.2); margin-left:15px;">
        إجمالي المنتجات المتوفرة: <?php echo $total_products; ?>
    </div>
    
    <a href="out_of_stock.php" style="text-decoration:none;">
        <div style="display:inline-block; background:#dc3545; color:#fff; padding:15px 40px; border-radius:10px; font-size:22px; font-weight:bold; box-shadow:0 4px 8px rgba(0,0,0,0.2); cursor:pointer;">
            المنتجات الغير متوفرة: <?php echo $total_out_products; ?> | إضغط هنا
        </div>
    </a>
</div>

<div class="sidebar_container">
<table dir="rtl">
<tr>
<th>رقــم المنتــج</th>
<th>صــورة المنتــج</th>
<th>عنــوان المنتـــج</th>
<th>سعــر المنتــج</th>
<th>الاحجــام المتوفــرة</th>
<th>توفــر المنتــج</th>
<th>الكمية المتوفرة</th>
<th>الاقســـام</th>
<th>تفاصيــل المنتــج</th>
<th>تعديــل المنتــج</th>
<th>حـذف المنتــج</th>
</tr>
<?php
$query = "SELECT * FROM product WHERE quantity > 0 ORDER BY id DESC";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><img src="../uploads/img/<?php echo $row['proimg']; ?>" style="width:70px;height:70px;object-fit:cover;"></td>
<td><?php echo htmlspecialchars($row['proname']); ?></td>
<td><?php echo number_format($row['proprice'],2); ?></td>
<td><?php $size = htmlspecialchars($row['prosize']);
 echo mb_strlen($size, 'UTF-8') > 50 ? mb_substr($size, 0, 50, 'UTF-8') . '...' : $size;
?></td>
<td><?php echo htmlspecialchars($row['prounv']); ?></td>
<td style="color:red; font-weight:bold; font-size:18px;"><?php echo $row['quantity']; ?></td>
<td><?php echo htmlspecialchars($row['prosection']); ?></td>
<td>
    <?php 
    $desc = htmlspecialchars($row['prodescrip']);
    echo mb_strlen($desc, 'UTF-8') > 50 ? mb_substr($desc, 0, 50, 'UTF-8') . '...' : $desc;
    ?>
</td>
<td>
    <a href="update.php?id=<?php echo $row['id'];?>">
        <button type="button" class="UPDATE">تعديــل المنتــج</button>
    </a>
</td>
<td>
    <a href="product.php?id=<?php echo $row['id'];?>&delete=1" onclick="return confirm('متأكد تبي تحذف المنتج <?php echo htmlspecialchars($row['proname']); ?>؟');">
        <button type="button" class="delet">حـذف المنتــج</button>
    </a>
</td>
</tr>
<?php
}
?>
</table>
</div> 
</body>
</html>