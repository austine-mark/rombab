<?php 
$a = 1;
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
    <title>Romantic Baboy | Admin Dashboard</title>
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
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-black">
  <?php if($row['user_role'] == '1' || $row['user_role'] == '2') { ?>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-white">Inventory System</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          
        <?php 
        $query_inventory_item = "SELECT item_name, stock FROM inventory WHERE stock < 20"; // Modify this query to select the required columns.
        // Execute the query.
        $result_inventory_item = mysqli_query($connection, $query_inventory_item);

        // Fetch the item data (item_name and stock) from the query result.
        $itemData = array();
        while ($row_item = mysqli_fetch_assoc($result_inventory_item)) {
            $itemData[] = $row_item;
        }
        ?>
        <div class="col-lg-6">
          <a href="inventory.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
              <div class="inner">
                <h4 class="font-weight-bold">Inventory Items</h4><p> (Low level stocks less than 20)</p>
                  <table class="table">
                    <?php
                      // Loop through the item data and display item_name and stock in table rows.
                    if(mysqli_num_rows($result_inventory_item) > 0) {
                      foreach ($itemData as $item) {
                        if ($item['stock'] <= 10) {
                          echo "<tr><td><span class='badge badge-pill badge-warning text-uppercase'>[WARNING] {$item['item_name']}</span></td>";
                          echo "<td> <span class='badge badge-pill badge-warning text-uppercase'>Stock: {$item['stock']}</span></td></tr>";
                        } else {
                        echo "<tr><td>{$item['item_name']}</td>";
                        echo "<td>Stock: {$item['stock']}</td></tr>";
                        }
                      }
                    } else {
                      echo "<tr><td class='text-center' colspan='2'>All stocks are in good levels.</td></tr>";
                    }
                    ?>
                  </table>
                </div>
                  <div class="icon text-white">
                  <i class="ion ion-ios-filing"></i>
                </div>
            </div>
          </a>
        </div>

        <?php
        date_default_timezone_set('Asia/Manila');
        $todayDate = date('Y-m-d');
        $query_inventory_reports = "SELECT inventory.item_name, users.name, log_reports.report_qty, log_reports.date_time
                                    FROM log_reports
                                    LEFT JOIN inventory ON inventory.item_id = log_reports.report_item_id
                                    LEFT JOIN users ON users.user_id = log_reports.report_user_id
                                    WHERE DATE(log_reports.date_time) = '$todayDate'";
        // Execute the query.
        $result_inventory_reports = mysqli_query($connection, $query_inventory_reports);
        ?>
        <div class="col-lg-6">
          <a href="inventory-history.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
              <div class="inner">
                <h4 class="font-weight-bold">Inventory Reports</h4>
                <p>Reports for <?php echo $todayDate;?>.</p>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th>User</th>
                      <th>Qty</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(mysqli_num_rows($result_inventory_reports) > 0) {
                      while ($row_reports = mysqli_fetch_assoc($result_inventory_reports)) {
                        echo "<tr>";
                        echo "<td>{$row_reports['item_name']}</td>";
                        echo "<td>{$row_reports['name']}</td>";
                        echo "<td>{$row_reports['report_qty']}</td>";
                        // Format date_time in 12-hour time format
                        $formattedTime = date('h:i A', strtotime($row_reports['date_time']));
                        echo "<td>{$formattedTime}</td>";
                        echo "</tr>";
                      }
                    }
                    else {
                      echo "<tr><td class='text-center' colspan='4'>No reports for today.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="icon text-white">
                <i class="ion ion-document-text"></i>
              </div>
            </div>
          </a>
        </div>

        <?php
        // Query to retrieve the menu data.
        $query_menu = "SELECT menu_name, menu_availability FROM menus";
        $result_menu = mysqli_query($connection, $query_menu);

        // Initialize counters for menus, activated menus, and deactivated menus.
        $menuCount = 0;
        $activatedCount = 0;
        $deactivatedCount = 0;

        while ($row_menu = mysqli_fetch_assoc($result_menu)) {
            $menuCount++; // Increment the menu count for each menu.
            
            // Check the menu_availability field and update the counts accordingly.
            if ($row_menu['menu_availability'] == 0) {
                $activatedCount++;
            } else {
                $deactivatedCount++;
            }
        }
        ?>
        <div class="col-lg-6">
          <a href="add-menu.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
                <div class="inner">
                  <h4 class="font-weight-bold">Menu Counts</h4>
                    <p>Restaurant menu counts for today.</p>
                      <table class="table">
                          <tr><td>Total Menus: <?php echo $menuCount; ?></td></tr>
                          <tr><td>Activated Menus: <?php echo $activatedCount; ?></td></tr>
                          <tr><td>Deactivated Menus: <?php echo $deactivatedCount; ?></td></tr>
                      </table>
                </div>
                <div class="icon text-white">
                    <i class="ion ion-android-restaurant"></i>
                </div>
            </div>
      </div>
    
        </div>
      </div>
    </section>

    <?php } if($row['user_role'] == '1' || $row['user_role'] == '2' || $row['user_role'] == '5') { ?>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-white">Appointment System</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">

        <?php 
        // Query to retrieve the menu data.
        $query_table = "SELECT * FROM users WHERE user_role = '4'";
        $result_table = mysqli_query($connection, $query_table);

        // Initialize counters.
        $tableCount = 0;
        $activatedCount = 0;
        $deactivatedCount = 0;
        $not_availableCount = 0;
        $occupiedCount = 0;
        $tableNamesNotAvailable = ''; // Initialize the table names string.
        $tableNamesActivate = '';
        $tableNamesDeactivate = '';
        $tableNamesOccupied = '';


        while ($row_table = mysqli_fetch_assoc($result_table)) {
            $tableCount++; // Increment the menu count for each menu.
            
            // Check the session_tb field and update the counts accordingly.
            if ($row_table['session_tb'] == 0) {
                $not_availableCount++;
                if ($not_availableCount > 1) {
                    $tableNamesNotAvailable .= ", "; // Add a comma if there are multiple not available tables.
                }
                $tableNamesNotAvailable .= $row_table['name']; // Add the table name.
            } else if ($row_table['session_tb'] == 1) {
                $activatedCount++;
                if ($activatedCount > 1) {
                  $tableNamesActivate .= ", ";
                }
                $tableNamesActivate .= $row_table['name'];
            } else if ($row_table['session_tb'] == 2) {
                $deactivatedCount++;
                if ($deactivatedCount > 1) {
                  $tableNamesDeactivate .= ", ";
                }
                $tableNamesDeactivate .= $row_table['name'];
            } else if ($row_table['session_tb'] == 3) {
                $occupiedCount++;
                if ($occupiedCount > 1) {
                  $tableNamesOccupied .= ", ";
                }
                $tableNamesOccupied .= $row_table['name'];
            } 
        }

        
        ?>

			      <a href="manage-appointment.php" class="small-box-footer">
              <div class="small-box bg-redbg text-white">
                <div class="inner">
                 <h5 class="font-weight-bold">Table Availability</h5>
                  <p>Check table availability</p>
                  <p>Total no. of tables: <?php echo $tableCount; ?></p>
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Status</th>
                            <th scope="col">No.</th>
                            <th scope="col">Table Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Activate</td>
                            <td><?php echo $activatedCount; ?></td>
                              <?php 
                              if($activatedCount === 0) { ?>
                              <td>There is/are no activated table.</td>
                              <?php } else { ?>
                                <td><?php echo $tableNamesActivate; ?></td>
                              <?php } ?>
                          </tr>
                          <tr>
                            <td>Deactivate</td>
                            <td><?php echo $deactivatedCount; ?></td>
                              <?php 
                              if($deactivatedCount === 0) { ?>
                              <td>There is/are no deactivated table.</td>
                              <?php } else { ?>
                                <td><?php echo $tableNamesDeactivate; ?></td>
                              <?php } ?>
                          </tr>
                          <tr>
                            <td>Not Available</td>
                            <td><?php echo $not_availableCount; ?></td>
                              <?php 
                              if($not_availableCount === 0) { ?>
                              <td>There is/are available table.</td>
                              <?php } else { ?>
                                <td><?php echo $tableNamesNotAvailable; ?></td>
                              <?php } ?>
                          </tr>
                          <tr>
                            <td>Occupied</td>
                            <td><?php echo $occupiedCount; ?></td>
                              <?php 
                                if($occupiedCount === 0) { ?>
                                <td>There is/are occupied table.</td>
                                <?php } else { ?>
                                  <td><?php echo $tableNamesOccupied; ?></td>
                                <?php } ?>
                          </tr>
                        </tbody>
                      </table>
                </div>
                <div class="icon text-white">
                  <i class="ion ion-clipboard"></i>
                </div>
             </div>
			      </a>
          
          
        </div>
    </section>
    <?php } ?>

      <?php
      if ($row['user_role'] == '1') { ?>
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 text-white">Add Account</h1>
              </div>
            </div>
          </div>
      </div>
  
      <section class="content">
        <div class="container-fluid">
        <div class="row">

          <div class="col-lg-6">
			      <a href="add-account.php" class="small-box-footer">
            <div class="small-box bg-redbg text-white">
              <div class="inner">
                <h3>Table</h3>
                <p>Add Account</p>
              </div>
              <div class="icon text-white">
                <i class="ion ion-person-add"></i>
              </div>
            </div>
			      </a>
          </div>

        </div>
        </div>
      </section>
    <?php } ?>

  </div>
</div>
</body>
<!-- Footer -->
<footer class="main-footer bg-black text-center">
    <div class="float-right d-none d-sm-block">
        <!-- Additional footer content or links can go here -->
    </div>
    Romantic Baboy – SM City Sta. Rosa Branch
 &copy; <?php echo date("Y"); ?>
</footer>
</html>