<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin LMS  | Dashboard</title>
     </head>
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
          Search Client
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Client</li>
          <li class="active">Search Client</li>
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
              <h3 class="box-title">Search By Telecaller</h3>
            </div>
            <div class="box-body">
              <div class="row">
              <?php echo form_open('admin/LeadController/getleadbytele'); ?>
                <div class="col-sm-4">
                  <p>Telecaller</p>
                  <div class="input-group margin">
                    <select class="form-control" name="ddtele" id="ddAdminsearch">
                      <option value="0" selected="true" disabled="true">Select Telecaller</option>
                      <?php 
                      foreach ($tele as $row) {
                       ?>
                       <option value="<?php echo $row['Id']; ?>"><?php echo $row['Name'];?></option>
                       <?php } ?>
                     </select>
                     <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="submit">Search</button>
                    </span>
                  </div>
                </div>
                </form>
                <?php echo form_open('admin/LeadController/getleadbyteleandclient'); ?>
                <div class="col-sm-4">
                  <p>Telecaller And Company</p>
                  <div class="form-group">
                    <select class="form-control" name="ddtele1" id="ddAdminsearch">
                      <option value="0" selected="true" disabled="true">Select Telecaller</option>
                      <?php 
                        foreach ($tele as $row) {
                       ?>
                       <option value="<?php echo $row['Id']; ?>"><?php echo $row['Name'];?></option>
                       <?php } ?>
                     </select>
                     </div>
                     <div class="form-group">
                     <select class="form-control" name="ddclient" id="ddAdminsearch">
                      <option value="0" selected="true" disabled="true">Select Company</option>
                      <?php 
                        foreach ($clients as $row) {
                       ?>
                       <option value="<?php echo $row['Id']; ?>"><?php echo $row['ClientCompany'];?></option>
                       <?php } ?>
                     </select>
                     </div>
                     <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="submit">Search</button>
                    </span>
                </div>
                </form>
              </div>
            </div>
          <br>

          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Clients</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" id="datatables">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Assigned To</th>
                        <th>Assigned By</th>
                        <th>Operations</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php 
                      if(isset($leads)){
                        foreach ($leads as $lead)
                        { 
                          echo "
                        <tr>
                            <td>{$lead['Name']}</td>
                            <td>{$lead['Email']}</td>
                            <td>{$lead['DateCreated']}</td>
                            <td>{$lead['ClientCompany']}</td>
                            <td>{$lead['Name']}</td>
                            <td><button class='btn btn-xs btn-success' type='submit'>Details</button></td></tr>";
                          }
                        }
                        else{
                        } 
                        ?>
                        </tbody>
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