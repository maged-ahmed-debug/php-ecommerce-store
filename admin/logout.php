<?php
// بدء الجلسة
session_start();

// يحذف كل متغيرات الجلسة
$_SESSION = array();

// نحذف كوكي الجلسة من المتصفح - يمنع الرجوع بسهم الرجوع
if(ini_get("session.use_cookies")){
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// حذف كل السشن اللي تم حفظها داخل المتصفح
session_unset();

// تدمير الجلسة نهائياً
session_destroy();

// منع الكاش - عشان ما يقدر يرجع بزرار الرجوع
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// إعادة توجيه لصفحة تسجيل الدخول
header('location:admin.php');
exit();
?>
<script>
// افتح صفحة تسجيل الدخول في نفس النافذة
window.location.replace("admin.php");
// حاول تقفل الصفحة الحالية
window.close();
</script>