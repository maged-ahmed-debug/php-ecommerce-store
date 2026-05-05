<?php
// لازم أول سطر قبل أي شي
$host="localhost";
$username="root";
$password="";
$dbname="shopping";
$conn= mysqli_connect($host,$username,$password,$dbname);

// احذف الـ echo حق الاتصال نهائياً
if(!$conn){
    die("فشل الاتصال بقاعدة البيانات");
}

// احذف هذا السطر نهائياً كان يحذف قسم كل مرة
// $DELETE="DELETE FROM section WHERE id=1";
// mysqli_query($conn,$DELETE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية للموقع</title>
    <link rel="stylesheet" href="./style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <header>
        <!---بداية ال logo-->
        <div class="logo">
            <h1>تســــوق أونلايــن</h1>
<img src="img/1.jpg">
<img src="img/2.jpg">
<img src="img/3.jpg">

        </div>
        <!--نهاية ال logo-->

        <!--بداية شريط البحث-->

<div class="search">
    <div class="search_bar">
        <form action="search.php" method="get">
<input type="text" class="search_input" name="search" placeholder="أدخــل كلمــة البحــث">
<button class="button_search" type="submit" name="btn_search">بحـــث</button>


<?php
// نحسب عدد طلبات الزبون لو مسجل دخول
$order_count = 0;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $count_q = mysqli_query($conn, "SELECT COUNT(*) as c FROM orders WHERE user_id='$user_id'");
    $order_count = mysqli_fetch_assoc($count_q)['c'];
}
?>

<!-- زر طلباتي جنب البحث -->
<a href="my_orders.php" class="btn-myorders">
    <i class="fa-solid fa-box"></i> طلباتي
    <?php if($order_count > 0){ ?>
        <span class="order-badge"><?php echo $order_count; ?></span>
    <?php } ?>
</a>

<style>
.btn-myorders{
    background: #ff6b6b;
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    margin-right: 10px;
    display: inline-block;
    position: relative;
    transition: all 0.3s;
}
.btn-myorders:hover{
    background: #ee5a5a;
    transform: scale(1.05);
}
.btn-myorders i{
    margin-left: 5px;
}
.order-badge{
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ffd700;
    color: #333;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border: 2px solid #fff;
}
</style>


        </form>

    </div>
</div>
        <!--نهاية شريط البحث-->
    </header>
   
  <nav>
     <!--بداية السوشال ميديا-->
    <div class="social">
        <ul>
            <li><a href="https://facebook.com/صفحة فسبوك صاحب المتجر" target_blank><i class="fa-brands fa-facebook"></i></a></li>
             <li><a href="https://m.me/صفحة فسبوك صاحب المتجر" target_blank><i class="fa-brands fa-facebook-messenger"></i></a></li>
              <li><a href="" target_blank><i class="fa-brands fa-instagram"></i></a></li>
             <li><a href="https://t.me/اسمك بالتلجرام" target_blank><i class="fa-brands fa-telegram"></i></a></li>
             <li><a href="https://wa.me/+966562458449" target_blank><i class="fa-brands fa-whatsapp"></i></a></li>
              <li><a href="https://youtube.com/@صفحة صاحب المتجر في اليتيوب" target_blank><i class="fa-brands fa-youtube"></i></a></li>
        </ul>
<!--target_blank  وظيفها انها تفتح رابط الصفحه في صفحة جديدة-->
        <!--نهاية السوشال ميديا-->
    </div>
<!---بداية القائمة الرئيسية-->
<div class="ection">
<ul>
  <li><a href="index.php">الرئيسيـة</a></li>
   
    <?php
$query="SELECT * FROM section";
$result=mysqli_query($conn,$query);
while($row=mysqli_fetch_assoc($result)){
    ?>
<li><a href="section.php?section=<?php echo $row['m'];?>"><?php echo $row['m']; ?> </a></li>
   <?php
}
?>
   
</ul> 
    </div>
    <!--نهاية القائمة الرئيسية -->
    </nav>
    <!--بداية ديف الجزء الثالث الذي هو مضاف حديثا وسلة الشراء والمستخدم-->
    <div class="last_post">
        <h4>مضــاف حديثــــــاً</h4>
<ul>
    <?php
$query="SELECT * FROM product ORDER BY ID DESC LIMIT 5";
$result=mysqli_query($conn,$query);
while($row=mysqli_fetch_assoc($result)){

?>
    <li><a href="detalis.php?id=<?php echo $row['id'];?>">
    <span class="span-img">
<img src="uploads/img/<?php echo $row['proimg']; ?>">
    </span>
    </a> 
    </li>
    <?php
}
?>
</ul>
    

<!--سلة الشراء والمستخدم-->
<div class="cart">
    <ul>

        <li><a href="user/logout.php"><i class="fa-solid fa-user"></i></a></li>

       <!--سلة الشراء--->
<?php
// نجيب user_id من السيشن، لو مو مسجل نخليه 0 عشان العداد يصير 0
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
// نعد عدد الصفوف فقط للمستخدم الحالي بدل كل السلة
$select_icon = "SELECT COUNT(*) as total FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $select_icon);
if($result){
    $row_data = mysqli_fetch_assoc($result);
    $row_count = $row_data['total'];
}else{
   $row_count = 0;  
}
?>
        <li class="cart-icon"><a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
        <span class="cart-count"><?php echo $row_count ?></span>
    </li>

    
    </ul>
</div>
<!--نهاية سلة الشراء والمستخدم-->
</div>