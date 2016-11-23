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
          Search Lead
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
              <h3 class="box-title">Search Client</h3>
            </div><!-- /.box-header -->
            <!-- form start -->

          
              <?php echo form_open('admin/LeadController/getleadbyclient'); ?>
              <div class="box-body">
                <div class="row">
                      <div class="col-sm-4">
                      <p>Customer</p>
                  <div class="input-group margin">
                    <select class="form-control" name="ddclient" id="ddAdminsearch">
                      <option value="0" selected="true" disabled="true">Select Client</option>
                      <?php 
                          foreach ($clients as $row) {
                       ?>
                       <option value="<?php echo $row['Id']; ?>"><?php echo $row['ClientCompany'];?></option>
                       <?php } ?>
                    </select>
                    <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="submit">Search</button>
                    </span>
                  </div>
                      </div>
                      </div>
                      </div>
                      </form>

              <?php echo form_open('admin/LeadController/getleadbydate'); ?>
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
                          <td><button class='btn btn-xs btn-success'>Details</button></td></tr>";
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
       <script type="text/javascript">
            $(document).ready(function () {

              $('#txtfrom').datepicker({
                format: "yyyy/mm/dd"
              });

              $('#txtto').datepicker({
                format: "yyyy/mm/dd"
              });  

            });
          </script>
    </div>
    <!-- Optionally, you can add Slimscroll and FastClick plugins. 
          Both of these plugins are recommended to enhance the 
          user experience -->
        </body>
        </html> 