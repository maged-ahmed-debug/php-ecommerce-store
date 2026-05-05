<?php
require_once('file/auth_admin.php');

if(isset($_POST['proadd'])){
    $proname = mysqli_real_escape_string($conn, $_POST['proname']);
    $proprice = mysqli_real_escape_string($conn, $_POST['proprice']);
    $prosection = mysqli_real_escape_string($conn, $_POST['prosection']);
    $prodescrip = mysqli_real_escape_string($conn, $_POST['prodescrip']);
    $prosize = mysqli_real_escape_string($conn, $_POST['prosize']);
    $prounv = mysqli_real_escape_string($conn, $_POST['prounv']);
    $quantity = intval($_POST['quantity']);
    
    if(empty($proname) || empty($proprice) || empty($prosection) || empty($prodescrip) || empty($prosize) || $quantity == ''){
        echo'<script>alert("الرجـــاء ملــئ جميــع الحقــول");</script>';
    }
    else{
        $imageName = $_FILES['proimg']['name'];
        $imageTmp = $_FILES['proimg']['tmp_name'];
        $proimg = rand(0,10000)."_".basename($imageName);
        move_uploaded_file($imageTmp,"../uploads/img/" .$proimg);

        $query="INSERT INTO product(proname,proimg,proprice,prosection,prodescrip,prosize,prounv,quantity) VALUES('$proname','$proimg','$proprice','$prosection','$prodescrip','$prosize','$prounv','$quantity')";
        $result=mysqli_query($conn,$query);
        if($result){
            echo'<script> alert("تــــم إضافـــة المنتــج بنجـــاح"); window.location="product.php";</script>';
        }
        else{
            echo'<script> alert("لـــم يتــــم إضافـــة المنتــج بنجـــاح");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافــة منتـــج</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @media(max-width:768px){
            .form_product{width:95% !important;padding:15px !important;}
            input,select{width:100% !important;box-sizing:border-box;}
            .button{width:100% !important;font-size:18px !important;}
        }
        @media(max-width:480px){
            h1{font-size:20px !important;}
            label{font-size:14px !important;}
        }
    </style>
</head>
<body>
   <center>
<main>
<div class="form_product">
<h1>إضافــة منتـــج</h1>
<form action="addproduct.php" method="post" enctype="multipart/form-data">

<label for="name">عنوان المنتج</label>
<input type="text" name="proname" id="name" required>

<label for="file">صورة المنتج</label>
<input type="file" name="proimg" id="file" required>

<label for="price">سعر المنتج</label>
<input type="text" name="proprice" id="price" required>

<label for="description">تفاصيل المنتج</label>
<input type="text" name="prodescrip" id="description" required>

<label for="size">الاحجام المتوفرة</label>
<input type="text" name="prosize" id="size" required>

<label for="quantity">الكمية المتوفرة</label>
<input type="number" name="quantity" id="quantity" min="0" value="0" required>

<div>
<label for="form_control">الاقسام</label>
<select name="prosection" id="form_control" required>
    <option value="">اختر القسم</option>
    <?php
    $query="SELECT * FROM section ORDER BY m ASC";
    $result=mysqli_query($conn,$query);
    while($row=mysqli_fetch_assoc($result)){
        echo'<option value="'.$row['m'].'"> '.$row['m'].'</option>';
    }
    ?>
</select>
</div>
<br><br>
<button class="button" type="submit" name="proadd">أضف المنتج الان</button>
</div>
</form>
</main>
   </center> 
</body>
</html>