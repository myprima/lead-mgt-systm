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
        <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js'); ?>"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#datatables').dataTable();
            });
        </script>
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
                        Update Client Payment
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
                                    <h3 class="box-title">Manage Client</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->



                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <?php echo form_open('admin/ClientController/clientsbypackage'); ?>
                                            <p>Select Rate</p>
                                            <div class="input-group margin">
                                                <select class="form-control" name="ddpackage" id="ddAdminsearch">
                                                    <option value="0" disabled="true" selected>Select Package</option>
                                                    <?php
                                                    foreach ($package as $row) {
                                                        ?>
                                                        <option value="<?php echo $row['Package']; ?>"><?php echo $row['Package']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-info btn-flat" type="submit">Search</button>
                                                </span>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Admins</h3>
                                                </div><!-- /.box-header -->

                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 ">
                                                            <div class="table-responsive no-padding">

                                                                <table id="datatables" class="display table-bordered table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Email</th>
                                                                            <th>Total Amount</th>
                                                                            <th>Paid</th>
                                                                            <th>Balance</th>
                                                                            <th>Pay Amount</th>
                                                                            <th>Pay Date</th>
                                                                            <th>Operations</th>
                                                                        </tr></thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if (isset($clients)) {
                                                                            foreach ($clients as $client) {
                                                                                $balance = $client['Package'] - $client['Paid'];
                                                                                echo "<tr>" . form_open('telecaller/ClientController/updateclientpayment');
                                                                                echo "
                      <td>{$client['ClientCompany']}<input type='hidden' name='txtcompany' value='{$client['ClientCompany']}'>
                        <input type='hidden' name='txtId' value='{$client['Id']}'></td>
                      <td>{$client['Email']}<input type='hidden' name='txtemail' value='{$client['Email']}'></td>
                      <td>{$client['Package']}<input type='hidden' name='txtamount' value='{$client['Package']}'></td>
                      <td>{$client['Paid']}<input type='hidden' name='txtpaid' value='{$client['Paid']}'></td>
                      <td>{$balance}<input type='hidden' name='txtbalance' value='{$balance}'></td>
                      <td><input type='text' name='txtpayamount'  placeholder='Enter Payment Amount'></td>
                      <td><input type='text' class='txtpaydate' name='txtpaydate' placeholder='Select Date'></td>
                      <td><button type='submit' class='btn btn-xs btn-success'>Update</button></form></tr>";
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

                        <!-- REQUIRED JS SCRIPTS -->

                        <!-- jQuery 2.1.3 -->
                        <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
                        <!-- Bootstrap 3.3.2 JS -->
                        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                        <!-- AdminLTE App -->
                        <script src="dist/js/app.min.js" type="text/javascript"></script>

                        <script src="bootstrap-datepicker.js"></script>
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
                        </body>
                        </html>