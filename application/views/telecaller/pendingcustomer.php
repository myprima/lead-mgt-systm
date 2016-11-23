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
    </head>
    <body class="skin-blue">
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
                        Pending Recall
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Client</li>
                        <li class="active">Update Payment</li>
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
                                    <h3 class="box-title">Pending Recall</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->

                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Users</h3>
                                                    <div class="box-tools">
                                                        <div class="input-group">
                                                        </div>
                                                    </div>
                                                </div><!-- /.box-header -->
                                                <div class="box-body ">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="table-responsive no-padding">

                                                                <table id="datatables" class="display table-bordered table-condensed ">
                                                                    <thead> <tr>
                                                                            <th>Company Name</th>
                                                                            <th>RC Date</th>
                                                                            <th>Last feedBack</th>
                                                                            <th>Operations</th>
                                                                        </tr></thead><tbody>
                                                                        <?php
                                                                        if (isset($visitors)) {
                                                                            foreach ($visitors as $visitor) {
                                                                                echo "<tr>" . form_open('telecaller/VisitorController/pendingvisitorsbyid');
                                                                                echo "
                                    <td>{$visitor['CompanyName']}<input type='hidden' value='{$visitor['VisitorId']}' name='ddVisitorId'></td>
                                    <td>{$visitor['ReminderDate']}<input type='hidden' value='{$visitor['Id']}' name='Id'></td>
                                    <td>{$visitor['VisitorRemark']}<input type='hidden' value='{$visitor['VisitorRemark']}' name='txtvisitorlastfeedback'></td>
                                    <td><button type='submit' class='btn btn-xs btn-success'>Recall</button></td>
                                    </form>
                                  </tr> ";
                                                                            }
                                                                        } else {
                                                                            
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
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


                        <script type="text/javascript">
                            // When the document is ready
                            $(document).ready(function () {

                                $('.txtpaydate').datepicker({
                                    format: "yyyy/mm/dd"
                                });

                            });
                        </script>
                        <!-- Optionally, you can add Slimscroll and FastClick plugins. 
                              Both of these plugins are recommended to enhance the 
                              user experience -->

                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#datatablesx1').dataTable();
                            });
                        </script>
                        </body>
                        </html>