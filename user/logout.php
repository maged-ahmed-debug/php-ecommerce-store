<?php
session_start();
include('../include/connected.php');
if(isset($_SESSION['user_id'])){
    session_unset();

    session_destroy();

    echo '<script>alert("تــم تسجيـــل الخـــروج");window.location.href="login.php";</script>';
}
else{ echo '<script>alert("لــم تقــوم بتسجيـــل الدخــول بعـــد");window.location.href="login.php";</script>';


}


?>