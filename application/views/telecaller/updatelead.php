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
        <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js'); ?>"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#datatables').dataTable();
            });
        </script>
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
            <?php include("sidebar.php"); ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Update Lead
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Lead</li>
                        <li class="active">Update Lead</li>
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
                                    <h3 class="box-title">All Leads</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->

                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Clients</h3>
                                                </div><!-- /.box-header -->
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="table-responsive no-padding">

                                                                <table id="datatables" class="display table-bordered table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Email</th>
                                                                            <th>Date</th>
                                                                            <th>Assigned To</th>
                                                                            <th>Assigned By</th>
                                                                            <th>Operations</th>
                                                                        </tr></thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if (isset($leads)) {
                                                                            foreach ($leads as $lead) {
                                                                                echo "
                        <tr>
                          <td>{$lead['Name']}</td>
                          <td>{$lead['Email']}</td>
                          <td>{$lead['DateCreated']}</td>
                          <td>{$lead['ClientCompany']}</td>
                          <td>{$lead['Name']}</td>
                          <td><button class='btn btn-xs btn-success'>Details</button></td></tr>";
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

                            </div><!-- /.box -->

                            </section><!-- /.content -->
                        </div><!-- /.content-wrapper -->

                        <!-- Main Footer -->
                        <?php include("footer.php"); ?>

                    </div><!-- ./wrapper -->


                    </body>
                    </html>