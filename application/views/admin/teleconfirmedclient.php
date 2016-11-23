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
          Manage Customer
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Tele Caller</li>
          <li class="active">Confirmed Clients</li>
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
              <h3 class="box-title">Confirmed Clients</h3>
            </div><!-- /.box-header -->
            <!-- form start -->


            
              <div class="box-body">
                <div class="row">
                      <div class="col-sm-4">
                      <?php echo form_open('admin/ClientController/clientsbytelecaller'); ?>
                      <p>Select By TeleCaller</p>
                  <div class="input-group margin">
                    <select class="form-control" name="ddtelecaller" id="ddtelecaller">
                      <option value="0" disabled selected>Select TeleCaller</option>
                      <?php 
                          foreach ($email as $row) {
                       ?>
                       <option value="<?php echo $row['Id']; ?>"><?php echo $row['Name'];?></option>
                       <?php } ?>
                    </select>
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-info btn-flat" type="button">Search</button>
                    </span>
                  </div>
                  </form>
                      </div>
                      
                      </div>
                      </div>
                      

              <?php echo form_open('admin/ClientController/clientsbydate'); ?>
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
                      <th>TeleCaller</th>
                      <th>Client Name</th>
                      <th>Company</th>
                      <th>Client Type</th>
                      <th>Client Email</th>
                      <th>Client Contact</th>
                      <th>Package</th>
                      <th>Operations</th>
                    </tr></thead>
                    <tbody>
              <?php 

              if(isset($clients)){
              foreach ($clients as $client)
              { 
                
                echo "<tr>".form_open('admin/ClientController/clientsdetails');
                echo "
                  <td>{$client['Name']}</td>
                  <td>{$client['ClientName']}</td>
                  <td>{$client['ClientCompany']}</td>
                  <td>{$client['DealerType']}</td>
                  <td>{$client['Email']}<input type='hidden' value='{$client['Email']}' name='ddClient'></td>
                  <td>{$client['ClientContact']}</td>
                  <td>{$client['Package']}</td>
                  <td>";
                  echo "&nbsp; &nbsp; <button class='btn btn-xs btn-primary' type='submit'>Details</button></td></form>
                  </tr>";
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
        </body>
        </html>