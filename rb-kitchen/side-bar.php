<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="http://localhost:3000/assets/rombab-logo.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="http://localhost:3000/rb-kitchen/kitchen-index.php" class="d-block">ROMANTIC BABOY</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open m-1">
            <a href="http://localhost:3000/rb-kitchen/kitchen-index.php" class="nav-link <?php if($a==1){ echo 'active'; }?>">
              <i class="ion ion-speedometer nav-icon"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
		
		    <li class="nav-item has-treeview menu-open m-1">
            <a href="see-orders.php" class="nav-link <?php if($a==2){ echo 'active'; }?>">
               <i class="ion ion-android-restaurant nav-icon"></i>
              <p>
               See Orders
              </p>
            </a>
        </li> 
        <li class="nav-item has-treeview menu-open m-1">
            <a href="order-history.php" class="nav-link <?php if($a==3){ echo 'active'; }?>">
               <i class="ion ion-document-text nav-icon"></i>
              <p>
                Orders History
              </p>
            </a>
        </li> 
        <li class="nav-item has-treeview menu-open m-1">
            <a href="#" class="nav-link <?php if($a==4){ echo 'active'; }?>">
               <i class="ion ion-ios-paper nav-icon"></i>
              <p>
                Log Report
              </p>
            </a>
        </li> 
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>