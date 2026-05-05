<?php
session_start();
?>
<?php
include('../include/connected.php');

if(isset($_SESSION['user_id'])){
    echo '<script>alert("مرحبـا أنت مسجــل مسبقــاً في المتجــر يمكنــك الدخــول");
    window.location.href="index.php"; </script>';

}
if($_SERVER['REQUEST_METHOD']=="POST"){
    @$username=$_POST['username'];
    @$password=$_POST['password'];

    $user_query= "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result= mysqli_query($conn,$user_query);
    if(mysqli_num_rows($result) > 0){
        //استرجاع بيانات المستخدم
$user_data= mysqli_fetch_assoc($result);
//تخزين المستحدم في الجلسة id

$_SESSION['user_id']= $user_data['id'];
 echo '<script>alert("مرحبـا بـك أيهــا الزائــر تـفضــل بالدخــول الـى المتجــر ");
    window.location.href="../index.php"; </script>';
    
  
    }
    else{
        echo '<script>alert("من فضلـك تاكـد من أسـم المستحـدم وكلمـة المـرور هنـاك خطـا مـا");</script>';
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخــول مستخــدم</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="m">
    <div class="user_container">
        <h2>أهــلاً بـك في صفحــة الدخــول الى المتــجر</h2>
        <form action="login.php" method="POST">

        <div class="input-signup">
            <input type="text" name="username" placeholder="إدخــل إســم المستخــدم المسجــل في المتـجر" required>
        </div>



        <div class="input-signup">
            <input type="password" name="password" placeholder="إدخــل الرمــز الســري لاستخدامـك الموقـع" required>
</div>
<button type="submit" class="btn">دخــــول
</button>

</form>
<div class="footer">
    <p>عفـواً ليس لديـك حســاب في المتـجر : <a href="signup.php">تسجيـل مستخــدم جديــد</a></p>
</div>
</div>
</div>
</body>
</html>