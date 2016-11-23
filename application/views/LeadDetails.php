<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
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
<body class="skin-red">
  <div class="wrapper">

    <!-- Main Header -->
    <?php require 'cheader.php'; ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php require 'csidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Lead Details
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Client</li>
          <li class="active">Cient Details</li>
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
              <h3 class="box-title">Customer Details</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <div class="row">
                <div class="col-sm-4">
                  <?php echo form_open('CustomersController/customerdetail'); ?>
                  <p>Select Customer</p>
                  <div class="input-group margin">
                    <select class="form-control" name="txtleadid" id="txtleadid">
                      <option value="0" disabled selected>Select Customer</option>
                      <?php 
                      foreach ($leads as $row) {
                       ?>
                       <option value="<?php echo $row['Id']; ?>"><?php echo $row['leadname'];?></option>
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
                    <h3 class="box-title">Details</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                      <tr>
                        <th>Name</th>
                        <td><?php if(isset($leaddetail)) { echo $leaddetail[0]['leadname'];} else {echo '';} ?></td>
                      </tr>
                      <tr>
                        <th>Email</th>
                        <td><?php if(isset($leaddetail)) { echo $leaddetail[0]['Email'];} else {echo '';} ?></td>
                      </tr>
                      <tr>
                        <th>Contact No</th>
                        <td><?php if(isset($leaddetail)) { echo $leaddetail[0]['Contact'];} else {echo '';} ?></td>
                      </tr>
                      <tr> 
                        <th>Address</th>
                        <td><?php if(isset($leaddetail)) { echo $leaddetail[0]['Address'];} else {echo '';} ?></td>
                      </tr>
                      <tr>
                        <th>Description</th>
                        <td><?php if(isset($leaddetail)) { echo $leaddetail[0]['Description'];} else {echo '';} ?></td>
                      </tr>
                      <tr>
                        <th>Assigned By</th>
                        <td><?php if(isset($leaddetail)) { echo $leaddetail[0]['Name'];} else {echo '';} ?></td>
                        </tr>
                        <tr>
                          <th>Date Assigned</th>
                          <td><?php if(isset($leaddetail)) { echo $leaddetail[0]['DateCreated'];} else {echo '';} ?></td>
                        </tr>
                        <tr>
                          <th>Your Feedback</th>
                          <td><?php 
                          if(isset($leaddetail)) 
                          {
                              echo form_open('CustomersController/UpdateClientFeedback');
                              ?>
                              <input type='hidden' value='<?php echo $leaddetail[0]['Id']  ?>' name='txtclientid'>
                              <textarea name="txtfeedback" cols="30" rows="5" placeholder="Enter Your Feedback">
                              <?php 
                                if(!is_null($leaddetail[0]['ClientFeedback']))
                                {
                                  echo $leaddetail[0]['ClientFeedback'];
                                  }
                                  else
                                  {
                                    echo '';
                                  }
                              ?>
                              </textarea><br/>
                              <button type='submit' class='btn btn-success'>Submit</button>
                              </form>
                              <?php  
                            }
                            else {
                              echo '';
                            }
                           ?> 
                           
                             </td>
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
    <?php require 'cfooter.php'; ?>

  </div><!-- ./wrapper -->
        </body>
        </html>