<!DOCTYPE html>
<html>
<head>
<title></title>
   <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js');?>"></script> 
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css">
 <script src="<?php echo base_url('assets/plugins/jquery.dataTables.min.js');?>"></script>  
 <script type="text/javascript">
  $(document).ready(function() {
    $('#datatables').dataTable();
  });
  </script>
 <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
</head>
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
            All Customers
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> All Customers</li>
          </ol>
        </section>

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
                        echo "<tr>".form_open('CustomersController/customerdetail');
                        echo "
                          <td>{$lead['Name']}<input type='hidden' value='{$lead['Id']}' name='txtleadid'></td>
                          <td>{$lead['Email']}</td>
                          <td>{$lead['DateCreated']}</td>
                          <td>{$lead['ClientCompany']}</td>
                          <td>{$lead['Name']}</td>
                          <td><button type='submit' class='btn btn-xs btn-success'>Details</button></td></form></tr>";
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
     <?php include('cfooter.php');?>
  </body>
</html>