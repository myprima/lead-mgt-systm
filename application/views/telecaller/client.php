<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin LMS  | Dashboard</title>
        <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js'); ?>"></script> 

    </head>
    <body class="skin-blue">
        <div class="wrapper">

            <?php include('header.php'); ?>
            <?php include('sidebar.php'); ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Confirmed Clients
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Client</li>
                        <li class="active">Confirm Client</li>
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
                                    <h3 class="box-title">Client List</h3>
                                </div><!-- /.box-header -->


                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive no-padding">

                                                <table id="datatables" class="display table-bordered table-condensed">
                                                    <thead><tr>
                                                            <th>Client Name</th>
                                                            <th>Company</th>
                                                            <th>Client Type</th>
                                                            <th>Client Email</th>
                                                            <th>Client Contact</th>
                                                            <th>Package</th>
                                                            <th>Details</th>
                                                        </tr></thead><tbody>
                                                        <?php
                                                        if (isset($clients)) {
                                                            foreach ($clients as $client) {
                                                                echo "<tr>" . form_open('telecaller/ClientController/clientsdetails');
                                                                echo "
                    <td>{$client['ClientName']}<input type='hidden' value='{$client['Email']}' name='ddClient'></td>
                    <td>{$client['ClientCompany']}</td>
                    <td>{$client['DealerType']}</td>
                    <td>{$client['Email']}</td>
                    <td>{$client['ClientContact']}</td>
                    <td>{$client['Package']}</td>
                    <td><button type='submit' class='btn btn-xs btn-success'>more</button></td>
                    </form>
                    </tr>";
                                                            }
                                                        } else {
                                                            
                                                        }
                                                        ?>
                                                    </tbody></table>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
                        </div>         

                    </div><!-- /.box -->
                </section>
                <!-- /.content -->
            </div><!-- /.content-wrapper -->

            <!-- Main Footer -->
<?php include('footer.php'); ?>
        </div><!-- ./wrapper -->

        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 2.1.3 -->


        <script src="bootstrap-datepicker.js"></script>

    </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins. 
          Both of these plugins are recommended to enhance the 
          user experience -->
</body>
</html>