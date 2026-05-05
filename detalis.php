<?php
session_start();
include('file/header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانـــات المنتجــات</title>
    <link rel="stylesheet" href="style2.css">
 <style>   
.username{
 padding:4px 5px;
 text-align: right;
 color: #0079bb;   
 font-size: 16px;
 font-weight: bold;
}
.addto_cart{
    width: 200px;
}
@media only screen and (max-width: 768px){
    .container{
        flex-direction: column;
    }
    .product_img, .product_info{
        width: 100% !important;
    }
}
</style>
</head>

<body>
<main>

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0){
    $query_product = "SELECT * FROM product WHERE id='$id' AND quantity > 0";
    $result_product = mysqli_query($conn, $query_product);
    
    if(mysqli_num_rows($result_product) > 0){
        $row_product = mysqli_fetch_assoc($result_product);
    } else {
        echo "<script>alert('المنتج غير موجود أو نفذت الكمية'); window.location='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('رابط غير صحيح'); window.location='index.php';</script>";
    exit();
}
?>
<div class="container">
    <div class="product_img">
        <img src="uploads/img/<?php echo $row_product['proimg']; ?>" alt="<?php echo $row_product['proname']; ?>">
    </div>
    
    <div class="product_info">
        <h1 class="product_title"><?php echo htmlspecialchars($row_product['proname']); ?></h1>
        <h2 class="product_price"><?php echo $row_product['proprice']; ?> SR</h2>
        <h3><?php echo htmlspecialchars($row_product['prosize']); ?> &nbsp; المقاسات المتوفرة</h3>
        <h4>الكمية المتوفرة: <?php echo $row_product['quantity']; ?></h4>
        <p class="product_description"><?php echo htmlspecialchars($row_product['prodescrip']); ?></p>
        
<form action="cart.php" method="POST" onsubmit="return validateQty(this, <?php echo $row_product['quantity']; ?>)">
 <div class="qty_input">
<button class="qty_count_mins" type="button" onclick="decrementQty()">-</button>
<input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $row_product['quantity']; ?>" data-stock="<?php echo $row_product['quantity']; ?>">
<button class="qty_count_add" type="button" onclick="incrementQty()">+</button>
</div>
<br>

<input type="hidden" name="product_id" value="<?php echo $row_product['id'];?>">
<input type="hidden" name="h_name" value="<?php echo $row_product['proname'];?>">
<input type="hidden" name="h_price" value="<?php echo $row_product['proprice'];?>">
<input type="hidden" name="h_img" value="<?php echo $row_product['proimg'];?>">

<div class="supmit">
<button class="addto_cart" type="submit" name="add" value="add_cart"><i class="fa-solid fa-shopping-cart"></i>&nbsp;أضـف الى السلـة
</button>
</div>
</form>
        
    </div>
    
    <div class="bottom_section">
        <?php
        if(isset($_POST['add_comment'])){
            $comment = mysqli_real_escape_string($conn, $_POST['comment']);
            if(empty($comment)){
                echo '<script> alert("الرجــاء كتابــة التعليــق لان الحقــل فـارغ")</script>';
            } else {
                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                $user_query = mysqli_query($conn, "SELECT username FROM users WHERE id = '$user_id'");
                $user_data = mysqli_fetch_assoc($user_query);
                $username = isset($user_data['username']) ? $user_data['username'] : 'زائـر في المتجــر';
                $query_insert = "INSERT INTO comments (comment, product_id, username) VALUES ('$comment', '$id', '$username')";
                mysqli_query($conn, $query_insert);
                echo '<script>window.location="detalis.php?id='.$id.'";</script>';
            }
        }
        
        $query_comment = "SELECT * FROM comments WHERE product_id='$id' ORDER BY id DESC";
        $result_comment = mysqli_query($conn, $query_comment);
        ?>
        <div class="comment_info">
            <h5>هـل تريــد تقييــم هـذا المنتــج</h5>
            <form action="" method="post">
                <textarea name="comment" placeholder="مــن فضــلك قيــم هــاذا المنتــج" required></textarea>
                <button class="add_comment" type="submit" name="add_comment">إرســـال</button>
            </form>
            <h5>تقييمــات العمـــلاء</h5>
            <div class="comments">
                <?php
                if(mysqli_num_rows($result_comment) > 0){
                    while($row_comment = mysqli_fetch_assoc($result_comment)){
                         echo "<div class='username'>تـم تقيــم المنتــج من قبـل :&nbsp;" . htmlspecialchars($row_comment['username']) . "</div>";
                        echo "<div class='comment'>" . htmlspecialchars($row_comment['comment']) . "</div>";
                    }
                } else {
                    echo '<div class="comment">لا يوجـــد اي تعليقــــات الى الان</div>';
                }
                ?>            
            </div>
        </div>

        <div class="recently_added">
            <h4>منتجــات حديثــة</h4>
            <?php
            $query_recent = "SELECT * FROM product WHERE id!='$id' AND quantity > 0 ORDER BY RAND() LIMIT 3";
            $result_recent = mysqli_query($conn, $query_recent);
            while ($row_recent = mysqli_fetch_assoc($result_recent)){
            ?>
            <div class="added_img">
                <a href="detalis.php?id=<?php echo $row_recent['id']?>">
                    <img src="uploads/img/<?php echo $row_recent['proimg']; ?>" alt="<?php echo $row_recent['proname']; ?>">
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</main>

<script>
function incrementQty() {
    var qty = document.getElementById('quantity');
    var maxStock = parseInt(qty.getAttribute('data-stock'));
    if(parseInt(qty.value) < maxStock) {
        qty.value = parseInt(qty.value) + 1;
    } else {
        alert('الكمية المتوفرة من هذا المنتج هي ' + maxStock + ' فقط');
    }
}

function decrementQty() {
    var qty = document.getElementById('quantity');
    if(parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}

function validateQty(form, stock){
    var qty = parseInt(form.querySelector('input[name="quantity"]').value);
    if(qty > stock){
        alert('الكمية المتوفرة من هذا المنتج هي ' + stock + ' فقط');
        return false;
    }
    if(qty < 1){
        alert('الرجاء إدخال كمية صحيحة');
        return false;
    }
    return true;
}
</script>

</body>
</html>