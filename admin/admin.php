<?php
ini_set('session.cookie_lifetime',0);
session_start();
include('../include/connected.php');
if(isset($_SESSION['admin_id'])){
    header("Location: adminpanel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدخــول الى لوحـة التحكـم</title>
</head>
<style>
    body{
        margin:0;
        padding: 0;
        background-color:#f4f4f4;
    }
    .container{
        width: 350px;
        height: 300px;
        margin: 80px auto;
        padding: 30px;
        background-color: #b4fd97;
         border-radius: 10px;
        box-shadow:5 5 10px rgba(0, 0, 0, 0.1);
    }
    h1{
        text-align:center;
        margin-bottom:20px;
    }
    form{
        display:flex;
        flex-direction:column;
        align-items:center;
    }
    label{
        display:block;
        margin-bottom:5px;
        font-size:18px;
        font-weight: bold;
    }
    input[type="email"],[type="password"]{
        width: 100%;
        padding: 10px;
        border:1px solid #ccc;
        margin-bottom:15px;
    }
    button{
        width: 100%;
        padding: 10px 20px;
        background-color: #ab5454;
        color:#fff;
        border:none;
        cursor:pointer;
        font-size:20px;
        font-weight: bold;
    } 
    button:hover{
        background-color: #ff0909; 
    }
    /* بس ضفت ريسبونسف خفيف بدون ما أغير شكل */
    @media(max-width:480px){
        .container{width:90%;margin:40px auto;padding:20px;height:auto;}
    }
</style>

<body>
    <main>
        <?php
        @$ADemail =$_POST['email'];
        @$ADpassword=$_POST['password'];
        @$ADadd=$_POST['add'];

        if(isset($ADadd)) {
            if(empty($ADemail)||empty($ADpassword)){
                echo'<script>alert("الـرجــاء إدخـــال الإيـمــل وكلـمــة الســـر");</script>';      
            } else {
                $ADemail = mysqli_real_escape_string($conn, $ADemail);
                $query="SELECT * FROM admin WHERE email='$ADemail' LIMIT 1";
                $result=mysqli_query($conn,$query);
                
                if(mysqli_num_rows($result) == 1){
                    $admin = mysqli_fetch_assoc($result);
                    // يتحقق من كلمة السر مشفرة أو نص عادي
                    if(password_verify($ADpassword, $admin['password']) || $ADpassword === $admin['password']){
                        // لو نص عادي يشفرها تلقائي
                        if($ADpassword === $admin['password']){
                            $hashed = password_hash($ADpassword, PASSWORD_DEFAULT);
                            mysqli_query($conn, "UPDATE admin SET password='$hashed' WHERE id='{$admin['id']}'");
                        }
                        $_SESSION['admin_id']=$admin['id'];
                        $_SESSION['email']=$admin['email'];
                        echo'<script>alert ("أهـلا بك أيهـا المديــر سوف يتم نقلك الى لوحــة التحكـــم");</script>';
                        header("REFRESH:1; URL=adminpanel.php");
                        exit();
                    } else {
                        echo'<script>alert("الإيميل أو كلمة السر غير صحيحة");</script>';
                    }
                } else {
                    echo'<script>alert("الإيميل أو كلمة السر غير صحيحة");</script>';
                }
            }
        }
        ?>
<div class="container">
    <h1>تسجيل الدخول الى لوحـة التحكم</h1>
    <form action="admin.php" method="post">
        <label for="em">البريـــد الالكترونـــي</label>
        <input type="email" name="email" id="em"><br>
        <label for="pass">كلمــــــــة الســـــر</label>
        <input type="password" name="password" id="pass">
        <br>
        <button type="submit" name="add">تسجيـــــل الـدخــــول</button>
    </form>
</div>
</main>
</body>
</html>