
<html><body>
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
          <li class="header">TELECALLER TASKS</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="<?php echo site_url('telecaller/telecaller');?>"><span>Dashboard</span></a></li>
			  
            <li class="treeview">
              <a href="#"><span>Users </span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
			        <li><a href="<?php echo site_url('telecaller/telecaller/entry');?>">Call Entry</a></li>
              <li><a href="<?php echo site_url('telecaller/telecaller/reentry');?>">ReCall Entry</a></li>
              <li><a href="<?php echo site_url('telecaller/VisitorController/allpendingrecall');?>">All Pending Recall</a></li>
              <li><a href="<?php echo site_url('telecaller/telecaller/update');?>">Update Details</a></li>
              <li><a href="<?php echo site_url('telecaller/telecaller/usercallhistory');?>">Call History</a></li>
              </ul>
            </li>
			<li class="treeview">
              <a href="#"><span>Client </span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
			  <li><a href="<?php echo site_url('telecaller/telecaller/centry');?>">Confirm Client</a></li>
                <li><a href="<?php echo site_url('telecaller/telecaller/updateclient');?>">Update Details</a></li>
                <li><a href="<?php echo site_url('telecaller/telecaller/clientDetails');?>">Client Details</a></li>
                <li><a href="<?php echo site_url('telecaller/telecaller/clientpayment');?>">Update Payment</a></li>
                <li><a href="<?php echo site_url('telecaller/telecaller/renew');?>">Renew Client Package</a></li>
                
              </ul>
            </li>
			   <li class="treeview">
              <a href="#"><span>Lead </span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
        <li><a href="<?php echo site_url('telecaller/telecaller/newlead');?>">New Lead</a></li>
        <li><a href="<?php echo site_url('telecaller/LeadController/importlead');?>">Import Lead</a></li>
                <li><a href="<?php echo site_url('telecaller/telecaller/updatelead');?>">All Leads</a></li>
                
              </ul>
            </li>
      
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
</body>
</html>