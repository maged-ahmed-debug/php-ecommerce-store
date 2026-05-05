<?php
session_start();
include('file/header.php');

if(!isset($_SESSION['user_id'])){
    echo '<script>alert("مرحبـاً بـك يرجـى تسجيـل الدخــول أولاً"); window.location.href="user/login.php";</script>';
    exit();
}
$user_id = intval($_SESSION['user_id']);

// إضافة للسلة مع التحقق من الكمية
if(isset($_POST['add']) && $_POST['add'] == 'add_cart'){
    $product_id = intval($_POST['product_id']); 
    $productname = mysqli_real_escape_string($conn, trim($_POST['h_name']));
    $productprice = floatval($_POST['h_price']);
    $productimg = mysqli_real_escape_string($conn, trim($_POST['h_img']));
    $productquantity = intval($_POST['quantity']);
    
    $check_stock_q = mysqli_query($conn, "SELECT quantity FROM product WHERE id = '$product_id'");
    $stock_data = mysqli_fetch_assoc($check_stock_q);
    $available_stock = $stock_data['quantity'];
    
    if($productquantity > $available_stock){
        echo '<script>alert("الكمية المتوفرة من هذا المنتج هي '.$available_stock.' فقط"); window.history.back();</script>';
        exit();
    }
    
    $check = "SELECT id, quantity FROM cart WHERE product_id = $product_id AND user_id = '$user_id'";
    $result = mysqli_query($conn, $check);
    
    if(mysqli_num_rows($result) > 0){
        $existing = mysqli_fetch_assoc($result);
        $new_qty = $existing['quantity'] + $productquantity;
        if($new_qty > $available_stock){
            echo '<script>alert("الكمية الإجمالية في السلة تتجاوز المتوفر. المتوفر: '.$available_stock.'"); window.location.href="cart.php";</script>';
            exit();
        }
        $update = "UPDATE cart SET quantity = '$new_qty' WHERE id = '{$existing['id']}'";
        mysqli_query($conn, $update);
        echo '<script>alert("تم تحديث كمية المنتج في السلة"); window.location.href="cart.php";</script>';
        exit();
    }
    else{
        $insert = "INSERT INTO cart(user_id, product_id, name, price, img, quantity) 
                   VALUES('$user_id', '$product_id', '$productname', '$productprice', '$productimg', '$productquantity')";
        if(mysqli_query($conn, $insert)){
           echo '<script>alert("تمـت إضافــة المنتــج الـى السلــة بنجــاح"); window.location.href="index.php";</script>';
           exit();
        } else {
           die("خطأ في قاعدة البيانات: " . mysqli_error($conn));
        }
    }
}

// حذف من السلة
if(isset($_POST['delete_c'])){
    $ID = intval($_POST['id']);
    if($ID > 0){
        $query="DELETE FROM cart WHERE id='$ID' AND user_id='$user_id'";
        $delete =mysqli_query($conn,$query);
        if($delete){
            echo '<script> alert("تــم الحــذف بنجــاح"); window.location.href="cart.php";</script>';
        }
        else{
            echo '<script> alert("لــم يتــم الحــذف هنــاك خطــا مـــا");</script>';
        }
    }
}

// تحديث الكمية مع التحقق من المخزون
if(isset($_POST['update_quantity'])){
    $cart_id = intval($_POST['cart_id']); 
    $new_quantity = intval($_POST['quantity']);
    
    if($cart_id > 0 && $new_quantity > 0){
        $get_prod = mysqli_query($conn, "SELECT product_id FROM cart WHERE id='$cart_id' AND user_id='$user_id'");
        $prod_data = mysqli_fetch_assoc($get_prod);
        $product_id = $prod_data['product_id'];
        
        $check_stock_q = mysqli_query($conn, "SELECT quantity FROM product WHERE id = '$product_id'");
        $stock_data = mysqli_fetch_assoc($check_stock_q);
        $available_stock = $stock_data['quantity'];
        
        if($new_quantity > $available_stock){
            echo '<script>alert("الكمية المتوفرة من هذا المنتج هي '.$available_stock.' فقط"); window.location.href="cart.php";</script>';
            exit();
        }
        
        $update_q = "UPDATE cart SET quantity='$new_quantity' WHERE id='$cart_id' AND user_id='$user_id'";
        if(mysqli_query($conn, $update_q)){
            echo '<script>alert("تــم تحديــث الكميــة بنجـــاح"); window.location.href="cart.php";</script>';
            exit();
        } else {
            echo '<script>alert("لم يتــم تحديــث الكميــة هنــاك خطــا ما");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلــة التســوق</title>
</head>
<style>
*{
margin: 0;
padding: 0;
box-sizing: border-box;
}
h3{
    font-family: arial,sans-serif;
    color:black;
}
body{
     font-family: arial,sans-serif;
     background-color: #f5e4e4;
color:#333;
}
.cart_container{
    direction: rtl;
    width: 80%;
    margin: 50px auto;
    background-color: bisque;
    padding: 20px;
    box-shadow: rgba(0,0,0,0.2);
}
.cont_head{
    border-radius: 5px; 
    padding: 5px;
    width: 100%;
    height: 100px;
    background-color: rgba(168,168,236);
}
.cont_head img{
   width: 70px;
   height: 70px;
   float: left;
   border-radius: 20px; 
}
.cont_head h1{
    float: right;
    margin: 20px; 
}
.cart_table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.cart_table th, td{
padding: 15px;
text-align: center;
border: 1px solid #6d6c6c;
}
.cart_table th{
    background-color: rgb(255, 203, 141);
}
.cart_table img{
    width: 70px;
    height: 70px;
}
.cart_table td input{
    width: 50px;
    padding: 5px;
    text-align: center;
}
.re{
    border-radius: 10px; 
    background-color: rgb(253, 106, 87);
    color: #000000;
border: none;
padding: 10px 10px;
cursor: pointer;
}
.re:hover{
    background-color: rgb(255, 0, 0);
}
.cart_total h6{
color: black;
font-size: larger;
}
.cart_total button{
    padding: 2px 10px;
    transition: transform 0.3s ease;
}
.cart_total button:hover{
    transform: scale(1.2);
}
.remove{
font-size: 16px;
font-weight: bold;
background-color: rgb(255, 151, 24);
color: black;
width: 70px;
height: 32px;
}
input{
    font-size: 16px;
    font-weight: bold;
}
@media only screen and (max-width: 768px){
    .cart_container{
        width: 95%;
    }
    .cart_table{
        font-size: 12px;
    }
    .cart_table th, .cart_table td{
        padding: 5px;
    }
    .cart_table img{
        width: 40px;
        height: 40px;
    }
}
</style>
<body>
    <div class="cart_container">
        <div class="cont_head">
            <img src="img/21.jpg">
            <?php
$query_usrr="SELECT username FROM users WHERE id='$user_id'";
$result_user=mysqli_query($conn,$query_usrr);
if($result_user){
    if(mysqli_num_rows($result_user) > 0){
        while($row=mysqli_fetch_assoc($result_user)){
            echo"<h1>أهـلا بـك : ".$row ['username']."  : تشرفنــا بزيارتــك للمتجـــر  </h1>";
        }
    }
}
?>
        </div>

        <table class="cart_table">
        <tr>
            <th>صـورة المنتـج</th>
            <th>رقـم المنتـج</th>
            <th>اسـم المنتـج</th>
            <th>الكميــة</th>
            <th>السعــر</th>
            <th>الاجمالــي</th>
            <th>حــذف</th>
            <th>تعـديل</th>
        </tr>
        
        <?php
        $query = "SELECT * FROM cart WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $query);
        $total = 0;

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
        ?>
        <tr>
            <td><img src="uploads/img/<?php echo $row['img']; ?>"></td>
            <td><h3><?php echo $row['product_id']; ?></h3></td>
            <td><h3><?php echo $row['name']; ?></h3></td>
            <td><h3><?php echo $row['quantity']; ?></h3></td>
            <td><h3><?php echo $row['price']; ?></h3></td>
            <td><h3> SR <?php echo number_format($row['quantity'] * $row['price'], 2); ?></h3></td>
            
            <td>
                 <form action="cart.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                    <button class="remove" type="submit" name="delete_c">حــذف
                        <i class="fa-solid fa-trash"></i></button>
                </form>
            </td>

            <td>
<form action="cart.php" method="post">
   <input type="hidden" name="cart_id" value="<?php echo $row['id'];?>">
    <input type="number" name="quantity" value="<?php echo $row['quantity'];?>" min="1" max="99" required>
    <button class="remove" type="submit" name="update_quantity">تعـديل<i class="fa-solid fa-pen-to-square"></i></button>
</form>
            </td>
        </tr>
        <?php
                $total += $row['quantity'] * $row['price'];
            }
        } else {
            echo "<tr><td colspan='8'>السلة فاضية حالياً</td></tr>";
        }
        ?>
        </table>
        <br>
        <div class="cart_total">
            <h6> المجمـــوع : <?php echo number_format($total, 2); ?><span id="total"> SR </span></h6>
            <br>
            <a href="checkout.php">
                <button type="button" class="re">
                    <h2>إتمـــام الطلـــب</h2>
                </button>
            </a>
        </div>
    </div>
    <br><br>
</body>
</html>
<?php include('file/footer.php'); ?>