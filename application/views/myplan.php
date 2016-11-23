<!DOCTYPE html>
<html>

  <body class="skin-red">
    <div class="wrapper">


         <?php include("cheader.php");?>
      <!-- Left side column. contains the logo and sidebar -->
<?php include("csidebar.php");?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            My Plan
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
<section class="content">


       <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Client Details</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
             <div class="box-body">
                        <div class="row">
            <div class="col-xs-6">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">My Plan</h3>
                  <div class="box-tools">
                    <div class="input-group">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Name</th>
                      <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['ClientName'];} else {echo '';} ?></td>
                    </tr>
                    <tr>
                      <th>Email</th>
                      <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['Email'];} else {echo '';} ?></td>
                    </tr>
                    <tr>
                      <th>Package</th>
                      <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['Package'];} else {echo '';} ?></td>
                    </tr>
                    <tr> 
                      <th>Lead</th>
                      <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['TotalLeads'];} else {echo '';} ?></td>
                    </tr>
                    <tr>
                      <th>Assigned</th>
                      <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['AssignedLeads'];} else {echo '';} ?></td>
                    </tr>
                    <tr>
                      <th>Confirmed To</th>
                      <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['Name'];} else {echo '';} ?></td>
                    </tr>
                    <tr>
                      <th>Date Confirmed</th>
                      <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['DateCreated'];} else {echo '';} ?></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>



           </div><!-- /.box -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
     <?php include('cfooter.php');?>
  </body>
</html>