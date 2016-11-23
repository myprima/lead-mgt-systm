      
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
                   </div>
        <!-- Default to the left --> 
        <strong>Copyright &copy; 2015 <a href="#">LMS</a>.</strong> All rights reserved.
    </footer>
</div><!-- ./wrapper -->

<!-- jQuery 2.1.3 -->
<script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js'); ?>"></script>
<!-- jQuery UI 1.11.2 -->
<!--<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>-->
<script src="<?php echo base_url('assets/bootstrap/js/jquery-1.11.1.min.js'); ?>" type="text/javascript"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>

<script src="<?php echo base_url('assets/bootstrap/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/bootstrap/js/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>

<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>    
<!-- daterangepicker -->
<script src="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker.js'); ?>" type="text/javascript"></script>
<!-- datepicker -->
<script src="<?php echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<!-- iCheck -->
<script src="<?php echo base_url('assets/plugins/iCheck/icheck.min.js'); ?>" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/dist/js/app.min.js'); ?>" type="text/javascript"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins. 
      Both of these plugins are recommended to enhance the 
      user experience -->



<script type="text/javascript">
    $(document).ready(function () {
        $('#datatables').dataTable();
    });
</script>
</html>