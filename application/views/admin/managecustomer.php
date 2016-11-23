<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin LMS  | Dashboard</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
<body class="skin-purple">
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
          Manage Customer
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Client</li>
          <li class="active">Manage Client</li>
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
              <h3 class="box-title">Manage Client</h3>
            </div><!-- /.box-header -->
            <!-- form start -->


            
              <div class="box-body">
                <div class="row">
                      <div class="col-sm-4">
                      <?php echo form_open('admin/ClientController/clientsbypackage'); ?>
                      <p>Select Rate</p>
                  <div class="input-group margin">
                    <select class="form-control" name="ddpackage" id="ddAdminsearch">
                      <option value="0" disabled="true" selected>Select Package</option>
                        <?php 
                          foreach ($package as $row) {
                       ?>
                       <option value="<?php echo $row['Package']; ?>"><?php echo $row['Package'];?></option>
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
                      

              <?php echo form_open('admin/ClientController/manageclientsbydate'); ?>
              <div class="box-body">
                <div class="row">
               <div class="col-sm-3">
               <p>From Date</p>
                  <div class="input-group margin">
                      <input type="text" class="form-control" id="txtfrom" name="txtfrom" placeholder="Select From Date">
                  </div>
                      </div>
                      <div class="col-sm-4">
               <p>To Date</p>
                  <div class="input-group margin">
                      <input type="text" class="form-control" id="txtto" name="txtto" placeholder="Select To Date">
                    <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="submit">Search</button>
                    </span>
                  </div>
                 </div>
               </div>
               </div>
             </form>
             <br>

             <div class="box-body">
                        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Admins</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="datatables"> 
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Date</th>
                      <th>Rate</th>
                      <th>Confirmed To</th>
                      <th>Leads</th>
                      <th>Assigned</th>
                      <th>Operations</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(isset($clients)){
                      foreach ($clients as $client)
                      { 
                          echo "<tr>".form_open('admin/ClientController/clientsdetails');
                          echo "
                          <td>{$client['ClientCompany']}</td>
                          <td>{$client['Email']}<input type='hidden' value='{$client['Email']}' name='ddClient'></td>
                          <td>{$client['DateCreated']}</td>
                          <td>{$client['Package']}</td>
                          <td>{$client['Name']}</td>
                          <td>{$client['TotalLeads']}</td>
                          <td>{$client['AssignedLeads']}</td>";
                          if($client['IsActive'])
                          {
                            echo "<td><button class='btn btn-xs btn-success'>Enable</button>&nbsp;&nbsp;";
                          }
                          else
                          {
                            echo "<td><button class='btn btn-xs btn-success'>Disable</button>&nbsp;&nbsp;";
                          }
                          echo "<button class='btn btn-xs btn-success' type='submit'>Details</button></td></form></tr>";
                        }
                      }
                      else{
                      } 
                      ?>
                  </table>
                  </tbody>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>



           </div><!-- /.box -->

         </section><!-- /.content -->
       </div><!-- /.content-wrapper -->

       <!-- Main Footer -->
       <?php require 'footer.php'; ?>
       <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {

              $('#txtfrom').datepicker({
                format: "yyyy/mm/dd"
              });

              $('#txtto').datepicker({
                format: "yyyy/mm/dd"
              });  

            });
          </script>

    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->
    
    
    <!-- Optionally, you can add Slimscroll and FastClick plugins. 
          Both of these plugins are recommended to enhance the 
          user experience -->
        </body>
        </html>