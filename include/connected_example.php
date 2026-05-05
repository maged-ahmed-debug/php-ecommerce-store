<?php
$host="localhost";
$username="root";
$password="";
$dbname="shopping";
$conn= mysqli_connect($host,$username,$password,$dbname);
if(isset($conn)){
   // echo"تــم الاتصـــال بقاعـــدة البيانـــات بنجــــاح";
 } else{
        echo"لــم يتـم الاتصـــال بقاعـــدة البيانـــات";
}
?>