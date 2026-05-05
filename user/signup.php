<?php
session_start();
?>
<?php
include('../include/connected.php');

if(isset($_SESSION['user_id'])){
    echo '<script>alert("مرحبـا أنت مسجــل مسبقــاً في المتجــر");
    window.location.href="index.php"; </script>';

}
if($_SERVER['REQUEST_METHOD']=="POST"){
    @$username=$_POST['username'];
    @$email=$_POST['email'];
    @$password=$_POST['password'];

    $user_query= "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result= mysqli_query($conn,$user_query);
    if(mysqli_num_rows($result) > 0){
echo '<script>alert("مرحبـا أنت مسجــل مسبقــاً في المتجــر تـفضــل بالدخــول"); window.location.href="../index.php";</script>';
   
}
    else{
        $query= "INSERT INTO users (username,email,password) VALUE('$username','$email','$password')";
        $result=mysqli_query($conn,$query);
        echo'<script>alert("تــم تسجيـلك في المتجــر بنجــاح"); window.location.href="../index.php";</script>';
    }

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيـل مستحــدم جديــد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="m">
    <div class="user_container">
        <h2>مرحبـا بـك في تسجيـل مستخــدم جديــد</h2>
        <form action="signup.php" method="POST">

        <div class="input-signup">
            <input type="text" name="username" placeholder="إدخــل إســم المستخــدم" required>
        </div>

        <div class="input-signup">
            <input type="email" name="email" placeholder="إدخــل بريــدك الإلكترونــي" required>
        </div>

        <div class="input-signup">
            <input type="password" name="password" placeholder="إدخــل رمــز ســري لاستخدامـك المتــجر" required>
</div>
<button type="submit" class="btn">التسجيــل في المتــجر
</button>

</form>
<div class="footer">
    <p>إذا لديــك حســاب مسبقــاً في المتــجر إضغــط : <a href="login.php">دخــــول</a></p>
</div>
</div>
</div>
</body>
</html>