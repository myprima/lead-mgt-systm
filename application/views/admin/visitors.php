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
          Daily Call Records
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Tele Caller</li>
          <li class="active">Daily Call Records</li>
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
              <h3 class="box-title">Daily Call Records</h3>
            </div><!-- /.box-header -->
            <!-- form start -->


            <?php echo form_open('admin/VisitorController/visitorsbytelecaller'); ?>
              <div class="box-body">
                <div class="row">
                 <div class="col-sm-6">
                      <p>Select Telecaller</p>
                  <div class="input-group margin">
                    <select class="form-control" name="ddtelecaller" id="ddtelecaller">
                      <option value="0" disabled selected>Select Telecaller</option>
                      <?php 
                          foreach ($email as $row) {
                       ?>
                       <option value="<?php echo $row['Id']; ?>"><?php echo $row['Name'];?></option>
                       <?php } ?>
                    </select>
                    <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Search</button>
                    </span>
                     <span style="color:red;"><?php echo form_error('ddadminemail'); ?></span>
                  </div>
                      </div>
               </div>
             </form>
             <br>
            <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Tele Callers</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="datatables">
                  <thead>
                    <tr>
                      <th>TeleCaller</th>
                      <th>Company Name</th>
                      <th>Visitor Name</th>
                      <th>Visitor Type</th>
                      <th>Visitor Contact</th>
                      <th>Status</th>
                      <th>Record Date</th>
                      <th>Operations</th>
                    </tr></thead>
                    <tbody>
              <?php 

              if(isset($visitors)){
              foreach ($visitors as $visitor)
              { 
                echo "
                <tr>
                  <td>{$visitor['Name']}</td>
                  <td>{$visitor['CompanyName']}</td>
                  <td>{$visitor['VisitorName']}</td>
                  <td>{$visitor['VisitorType']}</td>
                  <td>{$visitor['VisitorContact']}</td>
                  <td>{$visitor['Status']}</td>
                  <td>{$visitor['EntryDate']}</td>
                  <td><button class='btn btn-xs btn-success'>Details</button></td>
                </tr> ";

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