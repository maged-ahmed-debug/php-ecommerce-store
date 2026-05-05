<?php
require_once('file/auth_admin.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "SELECT * FROM product WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if (!$row) {
        die('المنتج غير موجود');
    }
} else {
    die('معرف المنتج غير صحيح');
}

if (isset($_POST['update_pro'])) {
    $id_new = intval($_POST['id_new']);
    $proname = mysqli_real_escape_string($conn, $_POST['proname']);
    $proprice = mysqli_real_escape_string($conn, $_POST['proprice']);
    $prosection = mysqli_real_escape_string($conn, $_POST['prosection']);
    $prodescrip = mysqli_real_escape_string($conn, $_POST['prodescrip']);
    $prosize = mysqli_real_escape_string($conn, $_POST['prosize']);
    $prounv = mysqli_real_escape_string($conn, $_POST['prounv']);
    $quantity = intval($_POST['quantity']);

    if (empty($prodescrip)) {
        echo '<script> alert("الرجـاء إضافـة تفاصيـل التعديــل للمنتـج ");</script>';
    } else {
        $proimg = $row['proimg'];
        if (isset($_FILES['proimg']) && $_FILES['proimg']['error'] == 0) {
            $imageName = $_FILES['proimg']['name'];
            $imageTmp = $_FILES['proimg']['tmp_name'];
            $proimg = rand(0, 10000) . "_" . basename($imageName);
            move_uploaded_file($imageTmp, "../uploads/img/" . $proimg);
            if (file_exists("../uploads/img/" . $row['proimg']) && !empty($row['proimg'])) {
                unlink("../uploads/img/" . $row['proimg']);
            }
        }

        $query = "UPDATE product SET
            proname='$proname',
            proimg='$proimg',
            proprice='$proprice',
            prosection='$prosection',
            prodescrip='$prodescrip',
            prosize='$prosize',
            prounv='$prounv',
            quantity='$quantity'
            WHERE id=$id_new";

        $result = mysqli_query($conn, $query);
        if ($result) {
            echo '<script> alert("تم تعديل بيانات المنتج بنجاح"); window.location="product.php"; </script>';
        } else {
            echo '<script> alert("فشل في تعديل بيانات المنتج");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديــل المنتـــج</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @media(max-width:768px){
            .form_product{width:95% !important;padding:15px !important;}
            input,select{width:100% !important;box-sizing:border-box;}
            .button{width:100% !important;font-size:18px !important;}
            img{max-width:100% !important;height:auto;}
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
<h1>تعديــل بيانــات منتـــج رقم <?php echo $row['id']; ?></h1>
<form action="update.php?id=<?php echo $row['id']; ?>" method="post" enctype="multipart/form-data">

<input type="hidden" name="id_new" value="<?php echo $row['id']; ?>">

<label for="name">عنوان المنتج</label>
<input type="text" name="proname" id="name" value="<?php echo htmlspecialchars($row['proname']); ?>" required>

<label for="file">صورة المنتج</label>
<?php if(!empty($row['proimg'])): ?>
    <img src="../uploads/img/<?php echo $row['proimg']; ?>" width="150"><br>
<?php endif; ?>
<input type="file" name="proimg" id="file">

<label for="price">سعر المنتج</label>
<input type="text" name="proprice" id="price" value="<?php echo $row['proprice']; ?>" required>

<label for="description">تفاصيل المنتج</label>
<input type="text" name="prodescrip" id="description" value="<?php echo htmlspecialchars($row['prodescrip']); ?>" required>

<label for="size">الاحجام المتوفرة</label>
<input type="text" name="prosize" id="size" value="<?php echo $row['prosize']; ?>" required>

<label for="quantity">الكمية المتوفرة</label>
<input type="number" name="quantity" id="quantity" min="0" value="<?php echo $row['quantity']; ?>" required>

<label for="unv">توفر المنتج</label>
<input type="text" name="prounv" id="unv" value="<?php echo $row['prounv']; ?>" required>

<div>
<label for="form_control">الاقسام</label>
<select name="prosection" id="form_control" required>
    <?php
    $query_sec = "SELECT * FROM section ORDER BY m ASC";
    $result_sec = mysqli_query($conn, $query_sec);
    while ($sec = mysqli_fetch_assoc($result_sec)) {
        $selected = ($sec['m'] == $row['prosection']) ? 'selected' : '';
        echo '<option value="'.$sec['m'].'" '.$selected.'> '.$sec['m'].'</option>';
    }
    ?>
</select>
</div>
<br><br>
<button class="button" type="submit" name="update_pro">حفــظ بيانــات تعديــل المنتــج</button>
</div>
</form>
</main>
</center> 
</body>
</html>