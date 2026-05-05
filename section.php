<?php
session_start();
include('file/header.php');

if(!isset($_GET['section']) || empty($_GET['section'])){
    echo '<script>alert("القسم غير موجود"); window.location.href="index.php";</script>';
    exit();
}

$section_name = mysqli_real_escape_string($conn, $_GET['section']);
?>
<style>
.m{
    width: 100%;
    height: 70px;
    display: flex;
    background-color: beige;
    border-radius: 10px;
    text-align: center;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 20px  rgb(209, 169, 248);
    font-size: 20px;
    font-weight: bold;
    margin-top: 20px;
}
@media only screen and (max-width: 768px){
    .product{
        width: 46% !important;
        margin: 2% !important;
        float: right;
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
<main>

<?php
$query = "SELECT * FROM product WHERE prosection = '$section_name' AND quantity > 0";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
?>

<div class="product">
    <div class="product_img">
        <a href="detalis.php?id=<?php echo $row['id']; ?>">
            <img src="uploads/img/<?php echo $row['proimg']; ?>">
            <span class="unvailable"><?php echo $row['prounv']; ?></span>
        </a>
    </div>
    <div class="product_section">
        <a href="section.php?section=<?php echo $row['prosection']; ?>"><?php echo $row['prosection']; ?></a>
    </div><br>
    <div class="product_name">
        <a href="detalis.php?id=<?php echo $row['id']; ?>"><?php echo $row['proname']; ?></a>
    </div>
    <div class="product_price">
        <a href="detalis.php?id=<?php echo $row['id']; ?>"><?php echo $row['proprice']; ?> SR </a>
    </div>
    <div class="product_description">
        <a href="detalis.php?id=<?php echo $row['id']; ?>">
            <i class="fa-solid fa-eye"></i>&nbsp;لتفاصيــل المنتــج إضغط هنا
        </a>
    </div>
    
    <div class="qty_input">
        <form action="cart.php" method="POST" onsubmit="return validateQty(this, <?php echo $row['quantity']; ?>)">  
            <button class="qty_count_mins" type="button" onclick="decrementQty(this)">-</button>
            <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['quantity']; ?>" data-stock="<?php echo $row['quantity']; ?>">
            <input type="hidden" name="product_id" value="<?php echo $row['id'];?>">
            <input type="hidden" name="h_name" value="<?php echo $row['proname'];?>">
            <input type="hidden" name="h_price" value="<?php echo $row['proprice'];?>">
            <input type="hidden" name="h_img" value="<?php echo $row['proimg'];?>">
            <button class="qty_count_add" type="button" onclick="incrementQty(this)">+</button>
    </div>
    
    <br>
    <div class="submit">
        <button class="addto_cart" type="submit" name="add" value="add_cart">
            <i class="fa-solid fa-shopping-cart"></i>&nbsp;أضـف الى السلـة
        </button>
    </div>
        </form>
</div>

<?php
    }
}else{
    echo '<div class="m">لا توجد منتجات في هذا القسم حالياً</div>';
}
?>
</main><br><br><br>

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

<?php include('file/footer.php'); ?>