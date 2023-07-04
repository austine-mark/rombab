<?php 
$a = 4;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    //if(isset($_POST["submit"])){
    //$appointment = $_POST["appointment_id"];
    //$table_name = $_GET['save'];
    //$query_act = "UPDATE appointment SET table_id = $table_name WHERE appointment_id = $appointment";
    //$result_act = mysqli_query($connection, $query_act);
    //}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Appointment</title>
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
<div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

    <div class="content-wrapper bg-black">
    <div class="content p-4">

    <div class="container-fluid text-center p-4">
        <h1>Manage Appointment</h1>
    </div>

    <section class="home-section">

    <form action="" method="post">
        <table class="table table-hover table-bordered table-dark mt-5">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Table Number</th>
                    <th class="text-center" scope="col">Customer</th>
                    <th class="text-center" scope="col">Activate Table</th>
                    <th class="text-center" scope="col">Deactivate Table</th>
                    <th class="text-center" scope="col">Not Available Table</th>
                </tr>
            </thead>
                <tbody>
                    
                    <?php 
                    $appointment = "SELECT appointment.table_id, appointment.appointment_name, appointment.appointment_session, appointment_id,
                    users.user_id, users.name, users.session_tb, users.user_role 
                    FROM appointment INNER JOIN users 
                    ON appointment.appointment_id=users.user_id
                    WHERE users.user_role = '4'";
                    

                    $result_tb = mysqli_query($connection, "SELECT appointment.table_id, appointment.appointment_name, appointment.appointment_session, appointment_id,
                                                            users.user_id, users.name, users.session_tb, users.user_role 
                                                            FROM appointment INNER JOIN users 
                                                            ON users.user_id=appointment.appointment_id
                                                            WHERE users.user_role = '4'");
                    while ($row = mysqli_fetch_array($result_tb)) { ?> 
                        <tr>
                            <td> <?php echo $row['name'];
                                
                                if($row['session_tb'] == '0') {  ?>
                                    <p class="text-yellow font-weight-bold">[Not Available]</p>
                                <?php }
                                else if($row['session_tb'] == '1')  { ?>
                                    <p class="text-green font-weight-bold">[Activate]</p>
                                <?php }
                                else if ($row['session_tb'] == '2')  {?>
                                    <p class="text-red font-weight-bold">[Deactivate]</p>
                                <?php } ?>
                        
                            </td>
                            <td>
                                <?php if(($row['session_tb'] == '1' && $row['appointment_session'] == '2') || ($row['session_tb'] == '1' && ($row['table_id'] === null && $row['appointment_session'] === null))){  ?>
                                <select class="form-control" name="appointment_id" id="appointment">
                                        <option hidden value="0">Select customer here</option>
                                        <?php $result_dropdown = mysqli_query($connection, "SELECT * FROM appointment WHERE appointment_session IS NULL ");
                                        while ($dropdown = mysqli_fetch_assoc($result_dropdown)){
                                            echo '<option value="' . $dropdown['appointment_id'] . '">' . $dropdown['appointment_name'] . '</option>';
                                        }?>
                                </select>
                                <a name="save" type="submit" class="btn btn-primary mt-2" href="activate-table-edit.php?id_save=<?php echo $row["appointment_id"]; ?>&save=1&table=<?php echo $row["user_id"]; ?>">SAVE</a>
                                <?php } 
                                    else if ($row['session_tb'] == '1' && ($row['appointment_session'] == '1' && $row['table_id'] !== null)){ ?>
                                        <select class="form-control" name="appointment" id="appointment" disabled>
                                                <option> [OCCUPIED] <?php echo $row['appointment_name']; ?> </option>
                                        </select>
                                        <a name="delete-appointment" type="submit" class="btn btn-info mt-2" href="activate-table-edit.php?id_reset=<?php echo $row["appointment_id"]; ?>&reset=2&table_reset=<?php echo $row["user_id"]; ?>">RESET</a>
                                    <?php }
                                    else {?>
                                        <select class="form-control" name="appointment" id="appointment" disabled>
                                            <option>Not available</option>
                                        </select>
                                    <?php }?>

                                
                            </td>
                            <td class="text-center"><a href="activate-table-edit.php?id=<?php echo $row["user_id"]; ?>&session=1" name="activate-tbl" type="submit" class="btn btn-success">Activate</a></td>
                            <td class="text-center"><a href="activate-table-edit.php?id=<?php echo $row["user_id"]; ?>&session=2" name="deactivate-tbl" type="submit" class="btn btn-danger">Deactivate</a></td>
                            <td class="text-center"><a href="activate-table-edit.php?id=<?php echo $row["user_id"]; ?>&session=0" name="not-available-tbl" type="submit" class="btn btn-warning" >Not Available</a></td>
                        </tr>
                    <?php 
                    } 
                    ?>
                </tbody>
        </table>
    </form>
    </section>
    </div>
    </div>
</div>

</body>
</html>