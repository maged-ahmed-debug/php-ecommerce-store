<?php
require_once('file/auth_admin.php');

// حذف الزائر
if(isset($_GET['delete_id'])){
    $delete_id = intval($_GET['delete_id']);
    if($delete_id > 0){
        mysqli_query($conn, "DELETE FROM sales WHERE user_id='$delete_id'");
        mysqli_query($conn, "DELETE FROM cart WHERE user_id='$delete_id'");
        mysqli_query($conn, "DELETE FROM orders WHERE user_id='$delete_id'");
        mysqli_query($conn, "DELETE FROM comments WHERE username IN (SELECT username FROM users WHERE id='$delete_id')");
        mysqli_query($conn, "DELETE FROM users WHERE id='$delete_id'");
        echo '<script>alert("تم حذف الزائر وكل بياناته بنجاح"); window.location.href="visitors.php";</script>';
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>زائرين المتجر</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .visitors-container{width:90%;margin:30px auto;font-family:'Tahoma',Arial;}
        .page-title{text-align:center;font-size:28px;font-weight:bold;margin-bottom:25px;padding:15px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,0.2);}
        .stats-box{display:flex;justify-content:space-around;margin-bottom:25px;gap:15px;flex-wrap:wrap;}
        .stat-card{background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.1);text-align:center;flex:1;min-width:200px;}
        .stat-card i{font-size:35px;color:#667eea;margin-bottom:10px;}
        .stat-card .number{font-size:28px;font-weight:bold;color:#333;}
        .stat-card .label{font-size:14px;color:#666;margin-top:5px;}
        .visitors-table{width:100%;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 3px 10px rgba(0,0,0,0.1);}
        .visitors-table table{width:100%;border-collapse:collapse;}
        .visitors-table thead{background:#667eea;color:#fff;}
        .visitors-table th{padding:15px;text-align:center;font-size:16px;}
        .visitors-table td{padding:12px;text-align:center;border-bottom:1px solid #eee;}
        .visitors-table tbody tr:hover{background:#f8f9fa;}
        .badge-country{background:#28a745;color:#fff;padding:5px 12px;border-radius:15px;font-size:13px;display:inline-block;}
        .badge-phone{background:#17a2b8;color:#fff;padding:5px 12px;border-radius:15px;font-size:13px;direction:ltr;display:inline-block;}
        .btn-delete{background:#dc3545;color:#fff;padding:6px 14px;border-radius:5px;text-decoration:none;font-size:13px;font-weight:bold;border:none;cursor:pointer;transition:all 0.3s;}
        .btn-delete:hover{background:#c82333;transform:scale(1.05);}
        .btn-delete i{margin-left:5px;}
        .no-data{text-align:center;padding:40px;font-size:18px;color:#999;}
        .no-data i{font-size:50px;display:block;margin-bottom:15px;color:#ddd;}
        @media(max-width:768px){
            .visitors-container{width:95%;}
            .visitors-table{overflow-x:auto;display:block;}
            .visitors-table table{min-width:900px;}
            .stat-card{min-width:100%;margin-bottom:10px;}
            .page-title{font-size:20px;padding:10px;}
        }
        @media(max-width:480px){
            .visitors-table th,.visitors-table td{padding:8px;font-size:12px;}
            .badge-country,.badge-phone{font-size:11px;padding:4px 8px;}
            .btn-delete{font-size:11px;padding:5px 10px;}
        }
    </style>
</head>
<body>
<main>
<div class="visitors-container">
    <div class="page-title">
        <i class="fa-solid fa-users"></i> زائرين المتجر - مرتبين أبجدياً
    </div>

    <?php
    $total_users_q = mysqli_query($conn, "SELECT COUNT(*) as c FROM users");
    $total_users = mysqli_fetch_assoc($total_users_q)['c'];

    $total_sales_q = mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as c FROM sales");
    $total_buyers = mysqli_fetch_assoc($total_sales_q)['c'];

    $countries_q = mysqli_query($conn, "SELECT COUNT(DISTINCT country) as c FROM sales WHERE country != '' AND country IS NOT NULL");
    $total_countries = mysqli_fetch_assoc($countries_q)['c'];
    ?>

    <div class="stats-box">
        <div class="stat-card">
            <i class="fa-solid fa-user"></i>
            <div class="number"><?php echo $total_users; ?></div>
            <div class="label">إجمالي المسجلين</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-bag-shopping"></i>
            <div class="number"><?php echo $total_buyers; ?></div>
            <div class="label">عملاء اشتروا</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-globe"></i>
            <div class="number"><?php echo $total_countries; ?></div>
            <div class="label">دول مختلفة</div>
        </div>
    </div>

    <div class="visitors-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th><i class="fa-solid fa-user"></i> اسم الزائر</th>
                    <th><i class="fa-solid fa-envelope"></i> الإيميل</th>
                    <th><i class="fa-solid fa-globe"></i> الدولة</th>
                    <th><i class="fa-solid fa-phone"></i> رقم التلفون</th>
                    <th><i class="fa-solid fa-calendar"></i> تاريخ التسجيل</th>
                    <th><i class="fa-solid fa-trash"></i> حذف</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT 
                            u.id, 
                            u.username, 
                            u.email, 
                            u.`created-at` as reg_date,
                            s.country,
                            s.phone,
                            s.full_name
                          FROM users u
                          LEFT JOIN (
                              SELECT user_id, country, phone, full_name 
                              FROM sales 
                              GROUP BY user_id
                              ORDER BY id DESC
                          ) s ON u.id = s.user_id
                          ORDER BY u.username ASC";
                
                $result = mysqli_query($conn, $query);
                $counter = 1;

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        $reg_date = date('Y-m-d', strtotime($row['reg_date']));
                        $display_name = !empty($row['full_name']) ? $row['full_name'] : $row['username'];
                ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><strong><?php echo htmlspecialchars($display_name); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <?php if(!empty($row['country'])){ ?>
                            <span class="badge-country">
                                <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($row['country']); ?>
                            </span>
                        <?php } else { ?>
                            <span style="color:#dc3545;font-weight:bold;">
                                <i class="fa-solid fa-xmark"></i> لم يشتري
                            </span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if(!empty($row['phone'])){ ?>
                            <span class="badge-phone">
                                <i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?>
                            </span>
                        <?php } else { ?>
                            <span style="color:#dc3545;font-weight:bold;">
                                <i class="fa-solid fa-xmark"></i> لم يشتري
                            </span>
                        <?php } ?>
                    </td>
                    <td><?php echo $reg_date; ?></td>
                    <td>
                        <a href="visitors.php?delete_id=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('هل أنت متأكد من حذف الزائر <?php echo addslashes($display_name); ?>؟\nسيتم حذف كل طلباته وسلته!');">
                            <i class="fa-solid fa-trash"></i> حذف
                        </a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="7" class="no-data"><i class="fa-solid fa-users-slash"></i>لا يوجد زائرين حتى الآن</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</main>
</body>
</html>