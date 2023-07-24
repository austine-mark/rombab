<?php 
$a = 3;
session_start();
include '../conn.php';
  $id = $_SESSION['user_id'];
	$result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id'");
	$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Kitchen</title>
    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!--Icon-->
    <link rel="icon" type="image/x-icon" href="../../assets/rombab-logo.png">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Theme Style -->
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../node_modules/admin-lte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../../node_modules/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- JQuery -->
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../../node_modules/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../node_modules/admin-lte/js/adminlte.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>
    <div class="content-wrapper bg-black">
        <div class="content p-4">
            <div class="container-fluid text-center p-4">
                <h1>Orders History</h1>
            </div>
            <table class="table table-hover table-bordered table-dark mt-5">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Table No.</th>
                    <th class="text-center" scope="col">Products</th>
                    <th class="text-center" scope="col">Date and Time</th>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $result_tb = mysqli_query($connection, "SELECT * FROM `orders`
                    LEFT JOIN `users` ON orders.user_table = users.user_id
                    WHERE status = 1");
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <tr>
                            <td class="text-center w-25"><?php echo $row["name"]; ?></td>
                            <td class="w-50"><?php echo $row["total_products"]; ?></td>
                            <td class="text-center w-25"><?php echo $row["time_date"]; ?></td>
                        </tr>
                        <?php 
                    } 
                    ?>
                </tbody>  
        </table>
        </div>
    </div>
</body>
</html>