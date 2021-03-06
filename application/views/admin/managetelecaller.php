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
          Manage Tele Caller
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Tele Caller</li>
          <li class="active">Manage Tele Caller</li>
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
              <h3 class="box-title">Manage Tele Caller</h3>
            </div><!-- /.box-header -->
            <!-- form start -->


            <?php echo form_open('admin/TelecallerController/GetTeleCallerByEmailManage'); ?>
              <div class="box-body">
                <div class="row">
                 <div class="col-sm-6">
                      <p>Select Telecaller</p>
                  <div class="input-group margin">
                    <select class="form-control" name="ddadminemail" id="ddadminsearch">
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
                      <th style="display:none;">ID</th>
                      <th>User ID</th>
                      <th>Name</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Operations</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($adminsdetails)){
                      foreach ($adminsdetails as $admind)
                      { 
                        if($admind['IsActive']=='true')
                        {
                          $IsActive="Enabled";
                        }
                        else
                        {
                          $IsActive="Disabled";
                        }
                        echo "<tr>".form_open('admin/TelecallerController/updateisactive');
                        echo "
                          <td style='display:none;'>{$admind['Id']}<input type='hidden' name='txtId' value='{$admind['Id']}'></td>
                          <td>{$admind['TelecallerId']}<input type='hidden' name='txtteleid' value='{$admind['TelecallerId']}'></td>
                          <td>{$admind['Name']}<input type='hidden' name='txtname' value='{$admind['Name']}'></td>
                          <td>{$admind['DateCreated']}<input type='hidden' name='txtdatecreated' value='{$admind['DateCreated']}'></td>
                          <td><span class='label label-primary'>$IsActive</span><input type='hidden' name='txtisactive' value='$IsActive'></td>";
                          if($admind['IsActive']=='true')
                          {
                            echo "<td><button class='btn btn-xs btn-danger' type='submit'>Disable</button></td>";
                          }
                          else
                          {
                            echo "<td><button class='btn btn-xs btn-success' type='submit'>Enable</button></td>";
                          }
                          echo "</tr> 
                        </form>";
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