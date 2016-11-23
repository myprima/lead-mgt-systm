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
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
	 <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js');?>"></script> 
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/datepicker/datepicker3.css'); ?>" >
    <script src="<?php echo base_url('assets/plugins/bootstrap-datepicker.js');?>" type="text/javascript"></script>
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    <a href="../../../../../../Users/Dheeraj/Desktop/templates/AdminLTE-2.0.4/AdminLTE-2.0.4/registercustomer.html"></a>
    -->
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

     <?php include("header.php");?>
        <?php include("sidebar.php");?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Call Entry
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
            <li class="active">Call Entry</li>
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
                  <h3 class="box-title">Customer Enquiry form</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
				
                              
   <?php echo form_open('telecaller/VisitorController/insert'); ?>
			 <div class="box-body">
<div class="row">
 <div class="col-sm-6">
				 
				  <div class="form-group">
                      <label for="exampleInputEmail1">Company Name</label>
                      <input type="text" value="<?php echo set_value('txtcompanyname') ?>" name="txtcompanyname" class="form-control" id="exampleInputEmail1" placeholder="Enter Company Name">
                    <span style="color:red;"><?php echo form_error('txtcompanyname'); ?></span>
                    </div>
				   <div class="form-group">
                     <label for="exampleInputEmail1">Deals In</label>
                      <input type="text" value="<?php echo set_value('txtcompanytype') ?>" name="txtcompanytype" class="form-control" id="exampleInputEmail1" placeholder="Enter Company Type">
                    <span style="color:red;"><?php echo form_error('txtcompanytype'); ?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Contact No</label>
                      <input type="text"  maxlength="11" value="<?php echo set_value('txtcontact') ?>" name="txtcontact" class="form-control" id="exampleInputEmail1" placeholder="Enter Contact Number">
                   <span style="color:red;"><?php echo form_error('txtcontact'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email</label>
                      <input type="email" value="<?php echo set_value('txtemail') ?>" name="txtemail" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
                    
                    <span style="color:red;"><?php echo form_error('txtemail'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Address</label>
                      <textarea name="txtAddress" class="form-control" rows="3" placeholder="Enter Address">
                        <?php echo set_value('txtAddress') ?>
                      </textarea>
                    <span style="color:red;"><?php echo form_error('txtAddress'); ?></span>
                    </div>
                    </div>					
					<div class="col-sm-6">
          <div class="form-group">
                      <label for="exampleInputEmail1">TeleCaller Name: </label>
                      <input type="text" value="<?php echo $this->session->userdata('Name'); ?>"  name="txttelecallername" readonly="true" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
                      <input type="hidden" value="<?php echo $this->session->userdata('Id'); ?>"  name="txttelecallerid" readonly="true">
                    <span style="color:red;"><?php echo form_error('txttelecallername'); ?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Contact Person</label>
                      <input type="text" value="<?php echo set_value('txtcontactperson') ?>" name="txtcontactperson" class="form-control" id="exampleInputEmail1" placeholder="Enter Contact Person Name">
                   <span style="color:red;"><?php echo form_error('txtcontactperson'); ?></span>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Client Status</label>
                        <select class="form-control" name="ddStatus" name="ddClientStatus" id="ddClientStatus">
                        <option value="Pending">Pending</option>
                      <option value="Denied">Denied</option>
                      <option value="Approved">Approved</option>
                    </select>
                    
					</div>
        <div class="form-group">
                    <label>CallBack Date:</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
            <div class="hero-unit">
                      <input name="txtcallbackdate" value="<?php echo set_value('txtcallbackdate') ?>" type="text" class="form-control pull-right" id="example1">
                      <span style="color:red;"><?php echo form_error('txtcallbackdate'); ?></span>
            </div>
                    </div><!-- /.input group -->
                  </div>
                   <div class="form-group">
                      <label>Customer Feedback</label>
                      <textarea name="txtfeedback" class="form-control" rows="3" placeholder="Enter Client Feebback">
                      <?php echo set_value('txtfeedback') ?>
                      </textarea>
                    <span style="color:red;"><?php echo form_error('txtfeedback'); ?></span>
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
       <?php include("footer.php");?>
              <script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {
                
                $('#example1').datepicker({
                    format: "yyyy/mm/dd"
                });  
            
            });
        </script>
    </div><!-- ./wrapper -->
  </body>
</html>