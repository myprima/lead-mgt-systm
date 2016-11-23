<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
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
                        Call History
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Users</li>
                        <li class="active">Call History</li>
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
                                    <h3 class="box-title">Call History</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->


                                <?php echo form_open('telecaller/VisitorController/Visitorhistorybyid'); ?>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p>Search User</p>
                                            <div class="input-group margin">
                                                <select class="form-control" name="ddVisitorId" id="ddadminsearch">
                                                    <option value="0" disabled selected>Select Company</option>
                                                    <?php
                                                    foreach ($users as $row) {
                                                        ?>
                                                        <option value="<?php echo $row['Id']; ?>"><?php echo $row['CompanyName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                </span>
                                                <span style="color:red;"><?php echo form_error('ddVisitorId'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Tele Callers</h3>
                                                    <div class="box-tools">
                                                    </div>
                                                </div><!-- /.box-header -->
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 ">
                                                            <div class="table-responsive no-padding">

                                                                <table id="datatables" class="display table-bordered table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="display:none;">ID</th>
                                                                            <th>Company Name</th>
                                                                            <th>Contact Person</th>
                                                                            <th>Contact No</th>
                                                                            <th>Contact Date</th>
                                                                            <th>Recall Date</th>
                                                                            <th>User Remark</th>
                                                                            <th>Status</th>
                                                                        </tr>

                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if (isset($visitorhistory)) {
                                                                            foreach ($visitorhistory as $vh) {
                                                                                echo " 
                          <tr><td style='display:none;'>{$vh['Id']}</td>
                            <td>{$vh['CompanyName']}</td>
                            <td>{$vh['VisitorName']}</td>
                            <td>{$vh['VisitorContact']}</td>
                            <td>{$vh['DateCreated']}</td>
                            <td>{$vh['ReminderDate']}</td>
                            <td>{$vh['VisitorRemark']}</td>
                            <td>{$vh['Status']}</td>
                            </tr>";
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

                        <!-- Optionally, you can add Slimscroll and FastClick plugins. 
                              Both of these plugins are recommended to enhance the 
                              user experience -->
                        </body>
                        </html>