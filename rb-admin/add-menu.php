<?php 
$a = 11;
session_start();
include '../conn.php';
    $id = $_SESSION['user_id'];
    $result = mysqli_query($connection, "SELECT * FROM users where user_id = '$id' ");
    $row = mysqli_fetch_array($result);

$msg = " ";
if (isset($_POST["upload"])) {
    // Get all the submitted data from the form
    $image = $_FILES['menu-image']['name'];
    $menu_text = $_POST['menu-text'];
    $category = $_POST['menu-category'];

    // Check for redundant data before inserting
    $check_query = "SELECT * FROM menus WHERE menu_name = '$menu_text' AND menu_category = '$category'";
    $result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Redundant data found, show an error message
        $msg = "Menu item with the same name and category already exists.";
    } else {
        // No redundant data, proceed with insertion
        $target = "menu-images/" . basename($_FILES['menu-image']['name']);
        $insert_query = "INSERT INTO menus (menu_image, menu_name, menu_category) VALUES ('$image', '$menu_text', '$category')";

        if (move_uploaded_file($_FILES['menu-image']['tmp_name'], $target) && mysqli_query($connection, $insert_query)) {
            $msg = "Image uploaded successfully";
        } else {
            $msg = "There was a problem uploading image or inserting data.";
        }

        // Redirect back to the add-menu.php page after inserting
        header('Location: add-menu.php');
        exit();
    }
}

if (isset($_POST["confirm_update"])) {
    $menu_id = $_POST['update-id'];
    $menu_text = $_POST['update-name'];
    $category = $_POST['update-category'];
    
    // Check if a new image file is uploaded
    if ($_FILES['update-image']['name'] !== '') {
        // Update the image file and move it to the target directory
        $target = "menu-images/" . basename($_FILES['update-image']['name']);
        $image = $_FILES['update-image']['name'];

        if (move_uploaded_file($_FILES['update-image']['tmp_name'], $target)) {
            $update_query = "UPDATE `menus` SET menu_image = '$image', menu_name = '$menu_text', menu_category = '$category' WHERE menu_id = '$menu_id'";
        } else {
            // Handle the case when image upload fails
            $msg = "There was a problem updating the image.";
            // You can add further error handling here if needed.
        }
    } else {
        // No new image selected, update only the other fields
        $update_query = "UPDATE `menus` SET menu_name = '$menu_text', menu_category = '$category' WHERE menu_id = '$menu_id'";
    }

    // Execute the update query
    if (isset($update_query)) {
        mysqli_query($connection, $update_query);
        // Redirect back to the add-menu.php page after updating
        header('Location: add-menu.php');
        exit();
    }
}

if (isset($_POST["delete_btn"])) {
    $menu_id_to_delete = $_POST["delete_btn"];

    // Perform the deletion query
    $delete_query = "DELETE FROM `menus` WHERE menu_id = '$menu_id_to_delete'";
    mysqli_query($connection, $delete_query);

    // Redirect back to the add-menu.php page after deleting
    header('Location: add-menu.php');
    exit();
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
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" >

    <?php 
    include "top-bar.php";
    include "side-bar.php"; 
    ?>

    <div class="content-wrapper bg-black">
        <div class="content p-4">
            <div class="container-fluid text-center p-4">
                <h1>Add Menu</h1>
            </div>

            <form method="post" action="add-menu.php" enctype="multipart/form-data">
                <input type="hidden" name="size" value="1000000">
                <div class="form-group">
                    <label>Menu Image</label>
                    <input type="file" class="form-control" name="menu-image" required>
                </div>
                <div class="form-group">
                    <label>Menu Name</label>
                    <input type="text" class="form-control" name="menu-text" placeholder="Enter Menu Name" required>
                </div>
                <div class="form-group">
                    <label>Menu Category</label>
                    <select name="menu-category" class="form-control" id="category" required>
                        <option hidden value="">-----Select Here-----</option>
                        <option value="Samgyupsal">Samgyupsal</option>
                        <option value="Side Dishes">Side Dishes</option>
                        <option value="Others">Others</option>
                        <option value="New Offers">New Offers</option>
                    </select>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="upload" value="Add Menu">
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

            <table class="table table-hover table-bordered table-dark mt-2">
            <thead>
                <tr>
                    <th class="text-center" scope="col">Image</th>
                    <th class="text-center" scope="col">Name</th>
                    <th class="text-center" scope="col">Category</th>
                    <th class="text-center" scope="col">Action</th>
                </tr>
            </thead>
                <tbody id = "menu_table">
                <?php 
                    $view_menus = mysqli_query($connection, "SELECT * FROM menus ORDER BY menu_id DESC");
                    if(mysqli_num_rows($view_menus) > 0) {
                    while ($row = mysqli_fetch_array($view_menus)) { ?>
                    <form method="post" action="add-menu.php" enctype="multipart/form-data">
                        <tr id="<?php echo $row["menu_id"]; ?>">
                            <td style="display: none"><?php echo $row["menu_id"]; ?></td> <!--hidden-->
                            <td class="text-center w-25"><img src ='menu-images/<?php echo $row["menu_image"]; ?>' class="img-fluid img-thumbnail custom-image"></td>
                            <td class="text-center"><?php echo $row["menu_name"]; ?></td>
                            <td class="text-center"><?php echo $row["menu_category"]; ?></td>
                            <td class="text-center w-25">
                                <button type="button" class="btn btn-primary update_btn" id="update_btn">UPDATE</button>
                                <button type="submit" class="btn btn-danger" name="delete_btn" value="<?php echo $row["menu_id"]; ?>">DELETE</button>
                            </td>
                        </tr>
                    </form>
                    <?php } } else {?>
                        <tr>
                            <td class="text-center" colspan="4">No record found!</td>
                        </tr>
                    <?php } ?>
                </tbody>  
            </table>
        </div>
    </div>

    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="add-menu.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="update-id" id="update-id">

                        <div class="form-group">
                            <label> Menu Image </label>
                            <img src="" class="img-fluid img-thumbnail" id="image-preview">
                        </div>

                        <div class="form-group">
                            <label>Update Menu Image</label>
                            <input type="file" class="form-control" id="update-image" name="update-image">
                        </div>

                        <div class="form-group">
                            <label>Menu Name</label>
                            <input type="text" class="form-control" id="update-name" name="update-name" placeholder="Enter Menu Name" required>
                        </div>

                        <div class="form-group">
                            <label>Menu Category</label>
                            <select name="update-category" class="form-control" id="update-category" required>
                                <option hidden value="">-----Select Here-----</option>
                                <option value="Samgyupsal">Samgyupsal</option>
                                <option value="Side Dishes">Side Dishes</option>
                                <option value="Others">Others</option>
                                <option value="New Offers">New Offers</option>
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

        // Handle the image file and preview
        var imageUrl = $tr.find('img').attr('src');
        $('#image-preview').attr('src', imageUrl);
        $('#update-image').attr('data-preview', imageUrl);
        $('#update-name').val(data[2]);
        $('#update-category').val(data[3]);
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
</script>