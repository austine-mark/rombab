<?php 
$a = 2;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

    if (isset($_POST["upload_item"])) {
        // Get all the submitted data from the form
        $item_name = $_POST['item-name'];
        $description = $_POST['item-description'];
        $oum = $_POST['item-measure'];
        $stock = $_POST['item-stock'];
        $status = $_POST['item-status'];
    
        // Check for redundant data before inserting
        $check_query = "SELECT * FROM inventory WHERE LOWER(item_name) = LOWER('$item_name')";
        $result = mysqli_query($connection, $check_query);
    
        if (mysqli_num_rows($result) > 0) {
            // Redundant data found, show an error message
            echo '<script type="text/javascript">'; 
            echo 'alert("Menu item with the same name already exists.");'; echo 'window.location.href = "inventory.php";';
            echo '</script>';
        } else {
            // No redundant data, proceed with insertion
            $insert_query = "INSERT INTO inventory (item_name, item_desc, unit_of_measure, stock, item_status) 
                            VALUES ('$item_name', '$description', '$oum', '$stock', '$status')";
            $result_insert = mysqli_query($connection, $insert_query);
    
            // Redirect back to the add-menu.php page after inserting
            header('Location: inventory.php');
            exit();
        }
    }

    // Check if the update form is submitted
    if (isset($_POST["confirm_update"])) {
        // Get the updated data from the form
        $item_id = $_POST['update-id'];
        $item_name = $_POST['update-name'];
        $description = $_POST['update-description'];
        $oum = $_POST['update-measure'];
        $stock = $_POST['update-stocks'];
        $status = $_POST['update-status'];

        // Perform the UPDATE query to update the inventory record
        $update_query = "UPDATE inventory SET item_name='$item_name', item_desc='$description', unit_of_measure='$oum', stock='$stock', item_status='$status' WHERE item_id='$item_id'";
        $result_update = mysqli_query($connection, $update_query);

        if ($result_update) {
            // Redirect back to the inventory.php page after updating
            header('Location: inventory.php');
            exit(); 
        } else {
            // Handle the error if the update fails
            echo "Error updating inventory record: " . mysqli_error($connection);
        }
    }

    if (isset($_POST["delete_btn"])) {
        $item_id_to_delete = $_POST['delete_btn'];
    
        // Perform the DELETE query to delete the inventory record
        $delete_query = "DELETE FROM inventory WHERE item_id='$item_id_to_delete'";
        $result_delete = mysqli_query($connection, $delete_query);
    
        if ($result_delete) {
            // Redirect back to the inventory.php page after deletion
            header('Location: inventory.php');
            exit();
        } else {
            // Handle the error if the deletion fails
            echo "Error deleting inventory record: " . mysqli_error($connection);
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romantic Baboy | Inventory</title>
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
                <h1>Inventory</h1>
            </div>

            <form method="post" action="inventory.php" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col">
                        <label>Item Name</label>
                        <input type="text" class="form-control" name="item-name" placeholder="Enter Item Name" required>
                    </div>

                    <div class="form-group col">
                        <label>Description</label>
                        <select name="item-description" class="form-control" required>
                            <option hidden value="">-----Select Here-----</option>
                            <option value="Meats">Meats</option>
                            <option value="Ingredients">Ingredients</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Sauces">Sauces</option>
                        </select>
                    </div>
                    
                    <div class="form-group col">
                        <label>UOM</label>
                        <select name="item-measure" class="form-control" required>
                            <option hidden value="">-----Select Here-----</option>
                            <option value="kg">kg</option>
                            <option value="can">can</option>
                            <option value="pack">pack</option>
                            <option value="box">box</option>
                            <option value="gal">gal</option>
                            <option value="tub">tub</option>
                            <option value="case">case</option>
                        </select>
                    </div>

                    <div class="form-group col">
                        <label>Stocks</label>
                        <input type="number" class="form-control" name="item-stock" step="any" min="0.1" placeholder="Enter Number of Stock" required>
                    </div>

                    <div class="form-group col">
                        <label>Status</label>
                        <select name="item-status" class="form-control" required>
                            <option value="0">Activated</option>
                            <option value="1">Deactivated</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="upload_item" value="ADD ITEM">
                </div>
            </form>

            <!-- Search -->
            <div class="d-flex w-100 justify-content-end p-0">
                <div class="d-flex justify-content-end gap-2">
                    <div class="input-group mb-3 d-flex">
                        <button class="btn " type="button" name="query" disabled><i class="ion ion-ios-search-strong"></i></button>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search Here...">
                    </div>
                </div>
            </div>
            
            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered table-dark mt-2" id="sortTable">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">Item</th>
                        <th class="text-center" scope="col">Description</th>
                        <th class="text-center" scope="col">UOM</th>
                        <th class="text-center" scope="col">Stocks</th>
                        <th class="text-center" scope="col">Status</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                    <tbody id = "menu_table">
                    <?php 
                        $view_items = mysqli_query($connection, "SELECT * FROM inventory
                                                    INNER JOIN statuses ON statuses.status_id = inventory.item_status
                                                    ORDER BY item_id DESC");
                        if(mysqli_num_rows($view_items) > 0) {
                        while ($row = mysqli_fetch_array($view_items)) { ?>
                            <form method="post" action="inventory.php" enctype="multipart/form-data">
                                <tr>
                                    <td style="display: none"><?php echo $row["item_id"]; ?></td>
                                    <td><?php echo $row["item_name"]; ?></td>
                                    <td><?php echo $row["item_desc"]; ?></td>
                                    <td><?php echo $row["unit_of_measure"]; ?></td>
                                    <td><?php echo $row["stock"]; ?></td>
                                    <td><?php echo $row["status"]; ?></td>
                                    <td  class="w-25">
                                        <div class="row text-center">
                                            <div class="col"><button type="button" class="btn btn-primary update_btn" name="update_btn" id="update_btn">UPDATE</button></div>
                                            <div class="col"><button type="submit" class="btn btn-warning" name="delete_btn" value="<?php echo $row["item_id"]; ?>">DELETE</button></div> 
                                        </div>
                                    </td>
                                </tr>
                            </form>
                        <?php } } else {?>
                            <tr>
                                <td class="text-center" colspan="7">No record found!</td>
                            </tr>
                        <?php } ?>
                    </tbody>  
                </table>
            </div>
        </div>
    </div>
</div>
    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="inventory.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="update-id" id="update-id">

                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" class="form-control" id="update-name" name="update-name" placeholder="Enter Menu Name" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <select name="update-description" class="form-control" required>
                                <option value="Meats">Meats</option>
                                <option value="Ingredients">Ingredients</option>
                                <option value="Beverages">Beverages</option>
                                <option value="Sauces">Sauces</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>UOM</label>
                            <select name="update-measure" class="form-control" required>
                                <option value="kg">kg</option>
                                <option value="can">can</option>
                                <option value="pack">pack</option>
                                <option value="box">box</option>
                                <option value="gal">gal</option>
                                <option value="tub">tub</option>
                                <option value="case">case</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Stocks</label>
                            <input type="number" class="form-control" name="update-stocks" id="update-stocks" step="any" min="0.1" placeholder="Enter Number of Stock" required>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="update-status" class="form-control" required>
                                <option value="0">activate</option>
                                <option value="1">deactivate</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">CLOSE</button>
                        <button type="submit" name="confirm_update" class="btn btn-primary">UPDATE</button>
                    </div>
                </form>
            </div>
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
<script>
    $(document).ready(function () {
    $('.update_btn').on('click', function () {
        $('#editmodal').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();
        console.log(data);
        $('#update-id').val(data[0]);
        $('#update-name').val(data[1]);
        $('#update-description').val(data[2]);
        $('#update-measure').val(data[3]);
        $('#update-stocks').val(data[4]);
        $('#update-status').val(data[5]);
    });
    });

    $(document).ready(function(){  
           $('#search').keyup(function(){  
                search_table($(this).val());  
           });  
           function search_table(value){  
                $('#menu_table tr').each(function(){  
                     var found = 'false';  
                     $(this).each(function(){  
                          if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)  
                          {  
                               found = 'true';  
                          }  
                     });  
                     if(found == 'true')  
                     {  
                          $(this).show();  
                     }  
                     else  
                     {  
                          $(this).hide();  
                     }  
                });  
           }  
      });


    // Get references to both input elements
    const stocksInput = document.getElementById('update-stocks');
    const itemStockInput = document.getElementsByName('item-stock')[0];

    // Add event listener for Stocks input
    stocksInput.addEventListener('input', function() {
        const stocks = parseFloat(stocksInput.value);

        if (isNaN(stocks) || stocks < 0 || stocks === 0) {
            stocksInput.value = '0.1';
        }
    });

    // Add event listener for Item Stock input
    itemStockInput.addEventListener('input', function() {
        const itemStock = parseFloat(itemStockInput.value);

        if (isNaN(itemStock) || itemStock < 0 || itemStock === 0) {
            itemStockInput.value = '0.1';
        }
    });
</script>