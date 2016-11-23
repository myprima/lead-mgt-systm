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

    <?php include("header.php"); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php include("sidebar.php"); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Change Password
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Change Password</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">


       <div class="row">
        <!-- left column -->
        <div class="col-sm-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- form start -->
            
            <div class="box-header" style="text-align:center;">
             <h3 class="box-title">Change Password</h3>
           </div><!-- /.box-header -->
           <?php echo form_open('admin/adminController/changepassword'); ?>
            <div class="box-body">
              <div class="row">
               <div class="col-sm-4 col-sm-offset-4">

                 <div class="form-group">
                  <label for="exampleInputEmail1">Old Password</label>
                  <input type="password" name="txtoldpassword" class="form-control" id="exampleInputEmail1" placeholder="Enter old password">
                  <span style="color:red;"><?php echo form_error('txtoldpassword'); ?></span>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">New Password</label>
                  <input type="password" name="txtnewpassword" class="form-control" id="exampleInputEmail1" placeholder="Enter new password">
                  <span style="color:red;"><?php echo form_error('txtnewpassword'); ?></span>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Re-Enter Password</label>
                  <input type="password" name="txtconfirmpassword" class="form-control" id="exampleInputEmail1" placeholder="Re-endter new password">
                  <span style="color:red;"><?php echo form_error('txtconfirmpassword'); ?></span>
                </div>
                <div class="box-footer col-sm-offset-3">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <?php if($this->session->flashdata('msg')): ?>
                   <p style="color:red;"><?php echo $this->session->flashdata('msg'); ?></p>
                 <?php endif; ?>
               </div>
             </div>
           </div>
         </div>
       </form>
     </div>
   </div>
   <!-- /.box-body -->

 </div><!-- /.box -->

</section><!-- /.content -->

</div><!-- /.content-wrapper -->

<!-- Main Footer -->
<?php include("footer.php"); ?>
</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->



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