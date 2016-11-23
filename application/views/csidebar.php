<html>
<body>
<aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo base_url('assets/dist/img/user2-160x160.jpg');?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo $this->session->userdata('Name'); ?></p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <!-- search form (Optional) -->
          <form action="<?php echo site_url('pages/order');?>" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
          <li class="header">CLIENT TASKS</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="<?php echo site_url('ClientDashboardController');?>"><span>Dashboard</span></a></li>
            <li class="treeview">
              <a href="<?php echo site_url('MyplanController');?>"><span>My Plan </span></a>
              <a href="<?php echo site_url('CustomersController');?>"><span>All Customers </span></a>
              <a href="<?php echo site_url('CustomersController/customerdetailspage');?>"><span>Customer Details</span></a>
              <a href="<?php echo site_url('ManageProfileController');?>"><span>Change Password</span></a>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
</body>
</html>