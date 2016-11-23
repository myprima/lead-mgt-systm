      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo base_url('assets/dist/img/user2-160x160.jpg'); ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p>Admin</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <!-- search form (Optional) -->
          <form action="#" method="get" class="sidebar-form">
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
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="#"><span>Dashboard</span></a></li>
        <li class=""><a href="#"><span>New Orders</span></a></li>
      <li class="treeview">
              <a href="#"><span>Admin </span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
        <li><a href="<?php echo site_url('welcome/cadmin');?>">Create Admin</a></li>
                <li><a href="<?php echo site_url('welcome/upadmin');?>">Update Admin</a></li>
                <li><a href="<?php echo site_url('welcome/madmin');?>">Manage Admin</a></li>
              </ul>
            </li>
                  <li class="treeview">
              <a href="#"><span>Tele Caller </span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
              <li><a href="#">Daily Calls Record</a></li>
              <li><a href="<?php echo site_url('welcome/tcclient');?>">Confirmed Clients</a></li>
              <li><a href="<?php echo site_url('welcome/ctele');?>">Create Tele Caller</a></li>
                <li><a href="<?php echo site_url('welcome/upatetele');?>">Update Tele Caller</a></li>
                <li><a href="<?php echo site_url('welcome/mtele');?>">Manage Tele Caller</a></li>
                
              </ul>
            </li>            
            <li class="treeview">
              <a href="#"><span>Client</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="<?php echo site_url('welcome/mclient');?>">Manage Client</a></li>
                <li><a href="<?php echo site_url('welcome/cdetails');?>">Client Details</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><span>Lead </span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
        <li><a href="<?php echo site_url('welcome/searchlead');?>">Search Lead</a></li>
        <li><a href="<?php echo site_url('welcome/leadby');?>">Lead By Telecaller</a></li>
              </ul>
            </li>

      
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>