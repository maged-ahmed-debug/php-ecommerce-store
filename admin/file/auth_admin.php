<?php
// file/auth_admin.php
session_start();

// منع الكاش عشان زر الرجوع
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// إذا مش مسجل دخول كأدمن، ارجعه لصفحة الدخول
if(!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])){
    header("Location: admin.php");
    exit();
}

// نتأكد إن الأدمن موجود فعلاً بقاعدة البيانات
include('../include/connected.php');
$admin_id = intval($_SESSION['admin_id']);
$check = mysqli_query($conn, "SELECT id FROM admin WHERE id='$admin_id'");
if(mysqli_num_rows($check) == 0){
    session_destroy();
    header("Location: admin.php");
    exit();
}
?>