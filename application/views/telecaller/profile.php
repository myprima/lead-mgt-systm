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
           <?php echo form_open('telecaller/telecaller/changepassword'); ?>
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