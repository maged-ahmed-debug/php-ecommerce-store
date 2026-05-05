<?php
ini_set('session.cookie_lifetime',0);
session_start();
include('../include/connected.php');

if(!isset($_SESSION['admin_id'])){
    header('location:admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="style.css">
    <title>لوحــــــــة التحكــــــم</title>
    <style>
        /* ضفت ريسبونسف بس - ما لمست style.css حقك */
        @media only screen and (max-width:768px){
            .sidebar_container{flex-direction:column;}
            .sidebar{width:100% !important;position:relative !important;}
            .sidebar h1{font-size:18px;}
            .sidebar ul{display:flex;flex-wrap:wrap;justify-content:center;padding:10px 0;}
            .sidebar ul li{margin:5px;width:45%;}
            .sidebar ul li a{font-size:14px;padding:8px;}
            .content_sec{width:100% !important;padding:10px;margin:0 !important;}
            .content_sec form{padding:10px;}
            .content_sec input{width:100% !important;box-sizing:border-box;}
            table{font-size:12px;display:block;overflow-x:auto;}
            th,td{padding:5px;font-size:12px;}
            .delet{padding:5px 10px !important;font-size:12px !important;}
        }
        @media only screen and (max-width:480px){
            .sidebar ul li{width:100%;}
            .sidebar ul li a{font-size:13px;}
            .content_sec label{font-size:14px;}
            .add{width:100% !important;font-size:16px !important;}
        }
    </style>
</head>
<body>
<?php
@$m=$_POST['m'];
@$s=$_POST['s'];
@$id=intval($_GET['id']);

if(isset($s)){
    if(empty($m)){
        echo'<script> alert("الحقــل فـارغ الرجـاء ملـئ الحقــل");</script>';
    }
    elseif(mb_strlen($m,'UTF-8')>30){ 
        echo'<script> alert("إســم القســم طويــل جــداً");</script>';
    }
    else{
        $m = mysqli_real_escape_string($conn, $m);
        $query="INSERT INTO section (m) VALUES('$m')";
        $result=mysqli_query($conn,$query);
        echo'<script> alert("تــم إضافــة القســـم");</script>';
    }
}
?>
<?php
#حذف القسم section
if(isset($id) && $id > 0)
{
    $query="DELETE FROM section WHERE id='$id'";
    $delet=mysqli_query($conn,$query);
    if($delet){
        echo'<script> alert("تــم حـــذف القســم بنجــــاح"); window.location="adminpanel.php";</script>';
    } 
    else{
        echo'<script> alert("لــم يتــم حـــذف القســم بنجــــاح")</script>';
    }
}
?>

<!--sidebar start-->
<div class="sidebar_container">
<!--بداية div sidebarال -->
    <div class="sidebar">
<h1> لوحــة تحكــم الإدارة</h1>
<ul>
<li><a href="../index.php" target="_blank">الصفحــة الرئيسيــة  <i class="fa-solid fa-house"></i></a></li>
<li><a href="product.php" target="_blank">صفحــة المنتجـــات  <i class="fa-solid fa-shirt"></i></a></li>
<li><a href="addproduct.php" target="_blank">إضافـــــة منتـــــــج <i class="fa-solid fa-folder-plus"></i></a></li>
<li><a href="visitors.php" target="_blank">معلومات الاعضـاء  <i class="fa-sharp fa-solid fa-users"></i></a></li>
<li><a href="admin_orders.php" target="_blank">طلبــــــات الزبائــن  <i class="fa-solid fa-folder-open"></i></a></li>
<li><a href="sales.php" target="_blank">المبيعـــــــــــــــــات  <i class="fa-solid fa-hand-holding-dollar"></i></a></li>
<li><a href="logout.php" target="_self">تسجيـــل الخـــروج  <i class="fa-solid fa-right-from-bracket"></i></a></li>
</ul>
</div>
<!--نهاية div sidebarال -->
<!--بداية ديف ال  section   -->
<div class="content_sec">
<form action="adminpanel.php" method="POST">
    <label for="section">إضافـــة قســـم جديـــد</label>
<input type="text" name="m" id="section">

<button class="add" type="submit" name="s">إضافــة قســم</button>
</form>
<br>
<br>
<!--نهاية ديف ال  section   -->
<!--بداية ديف ال  tabe   -->
<table dir="rtl">
<tr>
<th>الرقـم التسلسلـي</th>
<th>اسـم القســم</th>
<th>حـذف القســم</th>
</tr>
<?php
$query="SELECT * FROM section ORDER BY id DESC";
$result=mysqli_query($conn,$query);
while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['m']); ?></td>
<td><a href="adminpanel.php?id=<?php echo $row['id']; ?>" onclick="return confirm('متأكد تبي تحذف القسم؟')"><button type="button" class="delet">حذف قسم</button></td>
</tr>
<?php
}
?>
</table>
</div>
<!--نهاية ديف ال  tabe   -->
<!--sidebar end--->
</body>
</html>