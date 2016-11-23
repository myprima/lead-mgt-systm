<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.2 -->
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Font Awesome Icons -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Ionicons -->
  <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />

  <!-- Theme style -->
  <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
        -->
        <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js');?>"></script>
        <link href="dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
      </head>
  <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the 
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |  
  |---------------------------------------------------------|
  
-->
<body class="skin-blue">
  <div class="wrapper">

    <!-- Main Header -->
    <?php require 'header.php'; ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php require 'sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Renew Package
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Client</li>
          <li class="active">Renew Package</li>
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
              <h3 class="box-title">Renew Package</h3>
            </div><!-- /.box-header -->
            <!-- form start -->

            

            <div class="box-body">
              <div class="row">
                <div class="col-sm-4">
                  <?php echo form_open('telecaller/ClientController/clientdetailsforrenew'); ?>
                  <p>Select Client</p>
                  <div class="input-group margin">
                    <select class="form-control" name="ddClient" id="ddClient">
                      <option value="0" disabled selected>Select Client</option>
                      <?php 
                      foreach ($clients as $row) {
                       ?>
                       <option value="<?php echo $row['Email']; ?>"><?php echo $row['ClientCompany'];?></option>
                       <?php } ?>
                     </select>
                     <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="submit">Search</button>
                    </span>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <br>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-6">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Plan Details</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                  <?php $attributes = array('class' => 'form-horizontal');
                   echo form_open('telecaller/ClientController/renewpackage',$attributes); ?>
                   <form action="" method="POST" role="form" class="form-horizontal">
                     <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Name :</label>
                       <div class="col-sm-8">
                       <input type="hidden" readonly="true" name="txtid" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['Id'];} else {echo set_value('txtid');} ?>" required="required">
                         <input type="text" readonly="true" name="txtname" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['ClientName'];} else {echo set_value('txtname');} ?>" required="required">
                       </div>
                     </div>
                     <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Email :</label>
                       <div class="col-sm-8">
                         <input type="text"  readonly="true" name="txtemail" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['Email'];} else {echo set_value('txtemail');} ?>" required="required">
                       </div>
                     </div>               
                     <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Amount :</label>
                       <div class="col-sm-8">
                         <input type="hidden" readonly="true" name="txtpaid" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['Paid'];} else {echo set_value('txtpaid');} ?>" required="required">
                         <input type="hidden" name="txtamount" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['Package'];} else {echo set_value('txtamount');} ?>" required="required">
                         <input type="text" name="txtnewamount" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['Package'];} else {echo set_value('txtnewamount');} ?>" required="required">
                          <span style="color:red;"><?php echo form_error('txtpaid'); ?></span>
                       </div>
                     </div>               
                     <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Total Leads :</label>
                       <div class="col-sm-8">
                         <input type="hidden" name="txttotalleads" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['TotalLeads'];} else {echo set_value('txttotalleads');} ?>" required="required">
                         <input type="text" name="txtnewtotalleads" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['TotalLeads'];} else {echo set_value('txtnewtotalleads');} ?>" required="required">
                            <span style="color:red;"><?php echo form_error('txttotalleads'); ?></span>
                       </div>
                     </div>
                     <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Assigned Leads :</label>
                       <div class="col-sm-8">
                         <input type="text"  readonly="true" name="txtassignedleads" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['AssignedLeads'];} else {echo set_value('txtassignedleads');} ?>" required="required">
                       </div>
                     </div>
                     <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Pending Leads :</label>
                       <div class="col-sm-8">
                         <input type="text"  readonly="true" name="txtpendingleads" id="input" class="form-control" value="<?php 
                         if(isset($clientdetail)) { 
                          $pending=$clientdetail[0]['TotalLeads']-$clientdetail[0]['AssignedLeads'];
                          echo $pending;} else {echo set_value('txtpendingleads');} 
                          ?>" required="required">
                        </div>
                      </div>
                      <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Confirmed to :</label>
                       <div class="col-sm-8">
                         <input type="text" readonly="true" name="txtconfirmedto" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['Name'];} else {echo set_value('txtconfirmedto');} ?>" required="required">
                       </div>
                     </div>                
                     <div class="form-group">
                       <label for="input" class="col-sm-4 control-label">Date Confirmed :</label>
                       <div class="col-sm-8">
                         <input type="text" readonly="true" name="txtdateconfirmed" id="input" class="form-control" value="<?php if(isset($clientdetail)) { echo $clientdetail[0]['DateCreated'];} else {echo set_value('txtdateconfirmed');} ?>" required="required">
                       </div>
                     </div>
                     <div class="form-group">
                     <div class="col-sm-3 col-sm-offset-4">
                       <button type="submit" class="btn btn-primary">Renew</button>
                    <?php if($this->session->flashdata('msg')): ?>
                     <p><?php echo $this->session->flashdata('msg'); ?></p>
                    <?php endif; ?>
                       </div>
                     </div>
                   </form>
                 </div><!-- /.box body-->
               </div><!-- /.box-->
               </div><!-- /.col -->
               <div class="col-xs-6">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Payment Details</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                      <tr>
                        <th>Total Amount</th>
                        <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['Package'];} else {echo '';} ?></td>
                      </tr>
                      <tr>
                        <th>Paid Amount</th>
                        <td><?php if(isset($clientdetail)) { echo $clientdetail[0]['Paid'];} else {echo '';} ?></td>
                      </tr>
                      <tr>
                        <th>Balance Amount</th>
                        <td><?php
                          if(isset($clientdetail))
                          {
                            $balance=$clientdetail[0]['Package']-$clientdetail[0]['Paid'];
                            echo $balance;
                          }
                          else {
                            echo '';
                          } 
                          ?>
                        </td>
                      </tr>
                    </table>
                  </div><!-- /.box-body -->
                </div>

                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Payment History</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                      <tr>
                        <th>Payment Date</th>
                        <th>Paid Amount</th>
                      </tr>
                      <?php 
                      if(isset($clientpayment))
                      {
                        foreach ($clientpayment as $payment)
                        {
                          echo "<tr>
                          <td>{$payment['Payment_Date']}</td>
                          <td>{$payment['Paid']}</td>
                        </tr>";
                      }
                    }
                    else
                    {
                      echo "<tr><td colspan=2>No Records to Display</td></tr>";
                    }
                    ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>
        </div><!-- /.box -->

      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Main Footer -->
    <?php require 'footer.php'; ?>

  </div><!-- ./wrapper -->

  <!-- REQUIRED JS SCRIPTS -->

  <!-- jQuery 2.1.3 -->
  <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- Bootstrap 3.3.2 JS -->
  <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js" type="text/javascript"></script>

  <script src="bootstrap-datepicker.js"></script>
  <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {

              $('#example1').datepicker({
                format: "dd/mm/yyyy"
              });  

            });
          </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins. 
          Both of these plugins are recommended to enhance the 
          user experience -->
        </body>
        </html>