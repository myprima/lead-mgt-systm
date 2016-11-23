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

      <script type="text/javascript">
      $(document).ready(function($) {


      });
        function StatusChange(str,strid){
            console.log(str);
            if(str=="0")
            {
                alert('Please Select Company!');
            }
            else
            {
              var email_id=str; 
              var temp_id=strid;
               $.ajax({
                 url: '<?php echo base_url(); ?>'+'index.php/telecaller/LeadController/updateclient',
                 type: 'POST',
                 dataType: 'json',
                 data: {ddClientId: email_id,id:temp_id},
               })
               .done(function(data) {
                console.log(data);
                
                //console.log(data['clients'][0]);
                //console.log(data['clients'][0]['TotalLeads']);
                //console.log(json_decode(data));
                //var prse = JSON.parse(data);
                //var dt=prse.clients;
               // console.log(dt);
               
/*                 $('#txtclientid').val(data['clients'][0]['Id']);
                 $('#txttotalleads').val(data['clients'][0]['TotalLeads']);
                 $('#txtassignedleads').val(data['clients'][0]['AssignedLeads']);
                 */
                 console.log("success");
               })
               .fail(function() {
                 console.log("error");
               })
               .always(function() {
                 console.log("complete");
               });
               
            }
        }
        
      </script>

<body class="skin-blue">
  <div class="wrapper">

   <?php include("header.php");?>
   <?php include("sidebar.php");?>

   <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        New Lead  
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Lead</li>
        <li class="active">Import Lead</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">


     <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
          <div class="box-body">
            <div class="row">
             <div class="col-xs-8 col-sm-offset-2">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Insert Leads By CSv</h3>
        </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                <?php echo form_open_multipart('telecaller/LeadController/insertbycsv');?>
                <input type="file" name="file" class="btn btn-file" />
                <div class="col-sm-4 col-sm-offset-2">
                      <input type="submit" name="submit" class="btn btn-primary" value="Add" />
                </div>
                </form>
                </div>
  </div>
  </div>
  </div>
  </div>
          <div class="box-body">
            <div class="row">
              <div class="col-xs-10 col-sm-offset-1">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Assign Leads</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Contact No</th>
              <th>Client</th>
              <th>Operations</th>
            </tr>
            <?php 
            if(isset($templead)){
              foreach ($templead as $lead)
              { 
                echo "<tr>".form_open('telecaller/LeadController/cancellead');
                echo "
                  <td>{$lead['Name']}<input type='hidden' value='{$lead['Name']}' name='txtname'>
                  <input type='hidden' value='{$lead['Id']}' name='txtId'></td>
                  <td>{$lead['Email']}<input type='hidden' value='{$lead['Email']}' name='txtemail'></td>
                  <td>{$lead['Contact']}<input type='hidden' value='{$lead['Contact']}' name='txtcontact'></td>
                  <td>
                <select class='form-control' name='txtclientcompany' id='ddVisitorId' onchange='StatusChange(this.value,{$lead['Id']});'>";
                  foreach ($client as $row) {
                     echo "<option value='{$row['Email']}'>{$row['ClientCompany']}</option>";
                    } 
                echo "</select> 
                  </td>
                  <td><input type='submit' value='cancel' class='btn btn-xs btn-danger'></td></form>
                </tr>";
              }
            }
            else{
            } 
            ?>
              </table>
              <?php echo form_open('telecaller/LeadController/insertlead'); ?>
              <button type="submit" class="btn btn-success">Assign All</button></form>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
          </div>
          </div>
          </div>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include("footer.php");?>

</div><!-- ./wrapper -->


</body>
</html>