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
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="bootstrap/css/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="bootstrap/css/ionicons-2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="dist/css/skins/skin-purple.min.css" rel="stylesheet" type="text/css" />

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
  <body class="skin-purple">
    <div class="wrapper">


<?php require 'header.php'; ?>
      <!-- Left side column. contains the logo and sidebar -->
<?php require 'sidebar.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Register Admin
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Admin</li>
            <li class="active">Register Admin</li>
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
                  <h3 class="box-title">Admin Registration form</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
				
                   <?php echo form_open('admin/adminController/insert'); ?>
                  <div class="box-body">
                  <div class="row">
                  <div class="col-sm-6">
				   <div class="form-group">
                      <label for="exampleInputEmail1">Name</label>
                      <input type="text" value="<?php echo set_value('txtname') ?>" name="txtname" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
                      <span style="color:red;"><?php echo form_error('txtname'); ?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Contact No</label>
                      <input type="text" maxlength="11" value="<?php echo set_value('txtContactNo') ?>" name="txtContactNo" class="form-control" id="exampleInputEmail1" placeholder="Enter Contact Number">
                      <span style="color:red;"><?php echo form_error('txtContactNo'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Address</label>
                      <textarea name="txtAddress" cols="30" rows="5" class="form-control" placeholder="Enter Address"><?php echo set_value('txtAddress') ?></textarea>
                      <span style="color:red;"><?php echo form_error('txtAddress'); ?></span>
                    </div>
                    </div>
                    <div class="col-sm-6">
					<div class="form-group">
                      <label for="exampleInputEmail1">Email Id:</label>
                      <input type="email" value="<?php echo set_value('txtEmail') ?>" name="txtEmail" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                      <span style="color:red;"><?php echo form_error('txtEmail'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" value="<?php echo set_value('txtPassword') ?>" name="txtPassword" class="form-control" id="exampleInputPassword1" placeholder="Enter Password">
                      <span style="color:red;"><?php echo form_error('txtPassword'); ?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputPassword1">Re-enter Password</label>
                      <input type="password" value="<?php echo set_value('txtConfPassword') ?>" name="txtConfPassword" class="form-control" id="exampleInputPassword1" placeholder="Confirm Password">
                      <span style="color:red;"><?php echo form_error('txtConfPassword'); ?></span>
                    </div>
                    </div>
                    </div>
                    </div>
                   <!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <?php if($this->session->flashdata('msg')): ?>
                     <p><?php echo $this->session->flashdata('msg'); ?></p>
                    <?php endif; ?>
                  </div>
                </form>
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
    
    <!-- Optionally, you can add Slimscroll and FastClick plugins. 
          Both of these plugins are recommended to enhance the 
          user experience -->
  </body>
</html>




