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
  <!-- Ionicons -->

  <!-- Theme style -->
  <!-- Theme style -->

    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
        -->

        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">
        <style type="text/css">
          @import url(http://fonts.googleapis.com/css?family=Droid+Serif);

          h2{
           text-align: center;
           font-size: 24px;
         }

         hr{

         }

         p{
          Color: Black;
          font-size: 16px;
          font-weight: bold;
        }

        #button{

        }

        #button:hover{
          background: linear-gradient(to bottom, #49c0e8 5%, #59d0f8 100%);
        }

        input[type=text]{
          margin-top: 5px;
          margin-bottom: 20px;
          width: 96%;
          border-radius: 5px;
          border: 0;
          padding: 5px 0;
        }

        #name,#email{
          padding-left: 10px;
        }

        input[type=submit]{
          width:30%;
          border: 1px solid #59b4d4;
          background: #0078a3;
          color: #eeeeee;
          padding: 3px 0px;
          border-radius: 5px;
          margin-left: 33%;
          cursor:pointer;
        }

        input[type=submit]:hover{
          border: 1px solid #666666 ;
          background: #555555;
          color: white;
        }

        .ui-dialog .ui-dialog-content {
          padding: 2em;
        }

        div.container{


        }

        div.main{


         float:left;




         border-radius: 2px;
         font-size: 13px;
         text-align: center;
       }



/* -------------------------------------
    CSS for sidebar (optional) 
    ---------------------------------------- */
    .fugo{
     float:right;
   }

 </style>


 <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.3.min.js');?>"></script> 
 <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
 <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script type="text/javascript">
         var a;

         $(document).ready(function(){

          function load_data(){

            $.ajax({
              url: '<?php echo base_url(); ?>'+'index.php/telecaller/telecaller/all_data',
              type: 'GET',
              data: {
              },
            })
            .done(function(data) {
        //alert(data);
        var prse = JSON.parse(data);
        console.log(prse);
        var html = '';
        var dt = prse.visitors;
        console.log("length= "+dt.length);
        if(dt.length<=0)
        {
          html+="<br /><center><b>No Approved Users left to create client</b></center><br />";
          $('#push_data').html(html);
        }
        else
        {
          html += "<div class='col-sm-12' ><div class='table-responsive' ><table class='table table-bordered table-condensed' id='datatables'><thead><tr><th>Company Name</th><th>Visitor Name</th><th>Visitor Type"+
          "</th><th>Visitor Contact</th><th>Status</th><th style='display:none'>Address</th><th style='display:none'>Email</th><th>Record Date</th><th>Operations</th></thead></tr><tbody>";
          console.log(dt[0].Id);
          for(var i =0;i< dt.length;i++){
            var kk = dt[i];
            html+="<tr class='id_"+dt[i].Id+"'>"+
            "<td>"+dt[i].CompanyName+"</td>"+
            "<td>"+dt[i].VisitorName+"</td>"+
            "<td>"+dt[i].VisitorType+"</td>"+
            "<td>"+dt[i].VisitorContact+"</td>"+
            "<td>"+dt[i].Status+"</td>"+
            "<td style='display:none'>"+dt[i].VisitorAddress+"</td>"+
            "<td style='display:none'>"+dt[i].Email+"</td>"+
            "<td>"+dt[i].EntryDate+"</td>"+
            "<td><button class='btn btn-xs btn-success'>Details</button>&nbsp"+
            "<button class='btn btn-xs btn-success btn_popup'  id='"+dt[i].Id+"'>Transfer</button></td>"+
            "</tr>";
            console.log(html);
          }

          html+='</tbody></table></div></div></div>';
          console.log(html);
          $('#push_data').html(html);

          $('#datatables').on('page.dt',repeat())
          .on('search.dt',repeat())
          .on('order.dt',repeat())
          .dataTable();
          repeat();
        }
        console.log("success inside update");
      })
.fail(function() {
  console.log("error");
})
.always(function() {
  console.log("complete");
});

}

load_data();
$(function() {
  a=$("#dialog-1").dialog({
   autoOpen: false,
 });
});

function repeat(){
  $('.btn_popup').on('click',function(){
    showpopup();

    var take_id = $(this).attr('id');
    console.log(take_id);
    var take_data = $('.id_'+take_id).children();
    $('#txtid').val(take_id);
    $('#txtcompanyname').val(take_data[0].innerText);
    $('#txtvisitorname').val(take_data[1].innerText);
    $('#txtvisitortype').val(take_data[2].innerText);
    $('#txtvisitorcontact').val(take_data[3].innerText);
    $('#txtvisitoraddress').val(take_data[5].innerText);
    $('#txtvisitoremail').val(take_data[6].innerText);
    console.log(take_data[0].innerText);

  });
}

function showpopup()
{
  //a.dialog( "open" );
  $("#myModal").modal('show');
}

	//validating Form Fields.....
	$("#submit").click(function(e){

   var price = $("#price").val();
   var leads = $("#totalleads").val();
   var pass = $("#pass").val();
   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
   if( price ==='' || leads ===''|| pass==='')
   {
    alert("Please fill all fields...!!!!!!");
    return false;
  }   
  else 
  {
   return true;
 }

});

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

    <!-- Main Header -->
    <?php  include("header.php");?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php  include("sidebar.php");?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Update Details / Create Client          
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Users</li>
          <li class="active">Update</li>
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
              <h3 class="box-title">Visitors List</h3>
              <?php if($this->session->flashdata('msg')): ?>
                <h2><span class="label label-info"><?php echo $this->session->flashdata('msg'); ?></span></h2>
              <?php endif; ?>
            </div><!-- /.box-header -->


            <div class="box-body table-responsive no-padding" id="push_data">

            </div><!-- /.box-body -->

          </div><!-- /.box -->

          <div class="main">
            <div id="dialog-1" title="Dialog Form">

            </div>
          </div>

          <div id="myModal" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(0, 166, 90);">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" style="color:red"></span></button>
                  <h4 class="modal-title"><b>Package Details</b></h4>
                </div>
                <?php echo form_open('telecaller/ClientController/insert'); ?>
                <div class="modal-body">
                  <input type="hidden" id="txtid" name="txtid" style="color:blue">
                  <input type="hidden" id="txtcompanyname" name="txtcompanyname">
                  <input type="hidden" id="txtvisitorname" name="txtvisitorname">
                  <input type="hidden" id="txtvisitortype" name="txtvisitortype">
                  <input type="hidden" id="txtvisitorcontact" name="txtvisitorcontact">
                  <div class="form-group">
                    <label for="lbltotalleads">Total Leads</label>
                    <input type="text" id="totalleads" name="totalleads"  style="border:1px solid;" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="lblprice">Price:</label>
                    <input type="text" id="price" name="price"  style="border:1px solid;" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="lbladvance">Advance Paid</label>
                    <input type="text" id="totalleads" name="advance" style="border:1px solid;" class="form-control">
                  </div>
                  <div class="form-group">
                  <label for="lblemail">Email Id:</label>
                    <input type="text" id="txtvisitoremail" name="txtvisitoremail"  style="border:1px solid;" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="lblpassword">Password</label>
                    <input type="password" id="pass" name="pass"  style="border:1px solid;" class="form-control">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>         
  </div><!-- /.box -->
</section>
<!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Main Footer -->
<?php  include("footer.php");?>



</div>
</body>
</html>