<?php
session_start();
include('include/connected.php');
include('file/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحــث عـن منتــج</title>
</head>
<style>
.notification{
    width: 70%;
    height: 35px;
    background-color: wheat;
    border: 2px solid  #003cff;
    margin: 140px 130px;
    padding: 10px;
    font-size: 40px;
    font-weight: bold;
    color: black;
    text-align:center;
}
@media only screen and (max-width: 768px){
    .product{
        width: 46% !important;
        margin: 2% !important;
        float: right;
    }
    .notification{
        width: 90%;
        margin: 140px 5%;
        font-size: 20px;
    }
}
@media only screen and (max-width: 480px){
    .product{
        width: 96% !important;
        margin: 2% !important;
        float: none;
    }
}
</style>
<body>
    
</body>
</html>
<?php
if(isset($_GET['btn_search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM product WHERE quantity > 0 AND (prodescrip LIKE '%$search%' OR proname LIKE '%$search%' OR id LIKE '%$search%' OR prounv LIKE '%$search%' OR proprice LIKE '%$search%')";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0){
        echo '<h3 style="padding: 20px;">نتائج البحث عن: ' . htmlspecialchars($search) . '</h3>';
        echo '<div style="display:flex; flex-wrap:wrap; gap:20px; padding:0 20px; width:100%; box-sizing:border-box;">';
        
        while($row = mysqli_fetch_assoc($result)){
            echo'<div class="product" style="width:calc(23% - 20px); box-sizing:border-box; border:1px solid #ddd; padding:10px; margin-bottom:20px;">
            <div class="product_img">
                <img src="uploads/img/'.$row['proimg'].'" style="width:100%; height:220px; object-fit:cover;">
                <span class="unvailable">'.$row['prounv'].'</span>
            </div>
            <div class="product_section"><a href="">'.$row['prosection'].'</a></div><br>
            <div class="product_name"><a href="">'.$row['proname'].'</a></div>
            <div class="product_price"><a href="">'.$row['proprice'].' ريال</a></div>
            <div class="product_description"><a href="detalis.php?id='.$row['id'].'"><i class="fa-solid fa-eye"></i>&nbsp;لتفاصيــل المنتــج إضغط هنا</a></div>
            
            <form action="cart.php" method="POST" onsubmit="return validateQty(this, '.$row['quantity'].')">
            <div class="qty_input" style="margin:10px 0;">
                <button class="qty_count_mins" type="button" onclick="decrementQty(this)">-</button>
                <input type="number" name="quantity" value="1" min="1" max="'.$row['quantity'].'" data-stock="'.$row['quantity'].'" style="width:50px; text-align:center;">
                <button class="qty_count_add" type="button" onclick="incrementQty(this)">+</button>
            </div>
            
            <input type="hidden" name="product_id" value="'.$row['id'].'">
            <input type="hidden" name="h_name" value="'.$row['proname'].'">
            <input type="hidden" name="h_price" value="'.$row['proprice'].'">
            <input type="hidden" name="h_img" value="'.$row['proimg'].'">
            
            <div class="submit">
                <button class="addto_cart" type="submit" name="add" value="add_cart" style="width:100%; padding:8px;"><i class="fa-solid fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;أضــف الـى السلــة</button>
            </div>
            </form>
            </div>';
        }
        
        echo '</div>';
        echo'<div style="height:100px;"></div>';
    }
    else{
       echo '<div class="notification" style="padding:20px; text-align:center; font-size:18px;">المنتــج الـذي تبحــث عنـه غيــر متوفــر حاليــا</div>';
    }
}
?>

<script>
function incrementQty(btn) {
    var input = btn.parentNode.querySelector('input[name="quantity"]');
    var maxStock = parseInt(input.getAttribute('data-stock'));
    if(parseInt(input.value) < maxStock) {
        input.value = parseInt(input.value) + 1;
    } else {
        alert('الكمية المتوفرة من هذا المنتج هي ' + maxStock + ' فقط');
    }
}

function decrementQty(btn) {
    var input = btn.parentNode.querySelector('input[name="quantity"]');
    if(parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
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

<?php
include('file/footer.php');
?>