<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
        
        <script src="<?php echo base_url('assets/plugins/jquery.dataTables.min.js'); ?>"></script> 
        <link rel="stylesheet" href="<?php echo base_url('assets/plugins/datepicker/datepicker3.css'); ?>">
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <link href="<?php echo base_url('assets/bootstrap/css/dataTables.bootstrap.css'); ?>" rel="stylesheet" type="text/css"/>
        
        
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url('assets/bootstrap/css/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet'); ?>" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo base_url('assets/bootstrap/css/ionicons-2.0.1/css/ionicons.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url('assets/dist/css/AdminLTE.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect.
        -->

        
        
        <link href="<?php echo base_url('assets/dist/css/skins/skin-blue.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url('assets/plugins/datepicker/datepicker3.css'); ?>" >
        
        <style>
            th, td { white-space: nowrap; }
            div.dataTables_wrapper {
                width: 940px;
                margin: 0 auto;
            }
            #datatables {width: 940px;}
        </style>
        <script type="text/javascript">
            $(document).ready(function () {
                function load_notifydata() {
                    $.ajax({
                        url: '<?php echo base_url(); ?>' + 'index.php/telecaller/VisitorController/Notify',
                        type: 'GET',
                        data: {},
                    })
                            .done(function (data) {
                                var prse = JSON.parse(data);
                                console.log(prse);
                                var result = prse.recallnotify;
                                var expiredpackageresult = prse.expiredpackagenotify;
                                var html = '';
                                if (result.length > 0 || expiredpackageresult.length > 0)
                                {
                                    if (result.length > 0)
                                    {
                                        console.log(result[0].CompanyName);
                                        html += "<li><a href='<?php echo base_url(); ?>index.php/telecaller/VisitorController/visitorpending'><i class='fa fa-users text-aqua'></i>Recall to " + result.length + " Users Pending</a></li>";
                                    }
                                    if (expiredpackageresult.length > 0)
                                    {
                                        console.log(expiredpackageresult[0].ClientCompany);
                                        html += "<li><a href='<?php echo base_url(); ?>index.php/telecaller/telecaller/renew'><i class='fa fa-users text-aqua'></i>" + expiredpackageresult.length + " Clients plan has expired</a></li>";
                                    }
                                }
                                else
                                {
                                    html += "<li><a><i class='fa fa-users text-aqua'></i>No Pending Notifications</a></li>";
                                }
                                /*  for(var i =0;i< result.length;i++){
                                 html +="<li><a href='<?php echo base_url(); ?>index.php/telecaller/VisitorController/visitorpending'><i class='fa fa-users text-aqua'></i> Recall to "+result[i].CompanyName +" Pending</a></li>";
                                 }*/
                                $('#notifydata').html(html);
                                $('#notifynumbers').html('You have ' + (result.length + expiredpackageresult.length) + ' notifications');
                                $('#notifydatatotal').html(result.length + expiredpackageresult.length);
                                console.log("success");
                            })
                            .fail(function () {
                                console.log("error");
                            })
                            .always(function () {
                                console.log("complete");
                            });

                }

                load_notifydata();

            });
        </script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
            <![endif]-->
    </head>
    <body>
        <header class="main-header">
            <!-- Logo -->
            <a href="index2.html" class="logo"><b>TeleCaller</b>LMS</a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <!--                 <li class="dropdown messages-menu">
                          Menu toggle button
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                          </a>
                          <ul class="dropdown-menu">
                            <li class="header">You have 4 messages</li>
                            <li>
                              inner menu: contains the messages
                              <ul class="menu">
                                <li>start message
                                  <a href="#">
                                    <div class="pull-left">
                                      User Image
                                      <img src="<?php echo base_url('assets/dist/img/user2-160x160.jpg'); ?>" class="img-circle" alt="User Image"/>
                                    </div>
                                    Message title and timestamp
                                    <h4>                            
                                      Support Team
                                      <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                    </h4>
                                    The message
                                    <p>Why not buy a new awesome theme?</p>
                                  </a>
                                </li>end message                      
                              </ul>/.menu
                            </li>
                            <li class="footer"><a href="#">See All Messages</a></li>
                          </ul>
                        </li>/.messages-menu -->

                        <!-- Notifications Menu -->
                        <li class="dropdown notifications-menu">
                            <!-- Menu toggle button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning" id='notifydatatotal'></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header" id='notifynumbers'></li>
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <ul class="menu" id='notifydata'>

                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <!-- Tasks Menu -->
                        <!--                 <li class="dropdown tasks-menu">
                          Menu Toggle Button
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                          </a>
                          <ul class="dropdown-menu">
                            <li class="header">You have 9 tasks</li>
                            <li>
                              Inner menu: contains the tasks
                              <ul class="menu">
                                <li>Task item
                                  <a href="#">
                                    Task title and progress text
                                    <h3>
                                      Design some buttons
                                      <small class="pull-right">20%</small>
                                    </h3>
                                    The progress bar
                                    <div class="progress xs">
                                      Change the css width attribute to simulate progress
                                      <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                        <span class="sr-only">20% Complete</span>
                                      </div>
                                    </div>
                                  </a>
                                </li>end task item                      
                              </ul>
                            </li>
                            <li class="footer">
                              <a href="#">View all tasks</a>
                            </li>
                          </ul>
                        </li> -->
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="<?php echo base_url('assets/dist/img/user2-160x160.jpg'); ?>" class="user-image" alt="User Image"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs"><?php echo $this->session->userdata('Name'); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="<?php echo base_url('assets/dist/img/user2-160x160.jpg'); ?>" class="img-circle" alt="User Image" />
                                    <p>
                                        Welcome <?php echo $this->session->userdata('Name'); ?> - TeleCaller
                                        <small></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!--                     <li class="user-body">
                                  <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                  </div>
                                  <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                  </div>
                                  <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                  </div>
                                </li> -->
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo site_url('telecaller/telecaller/profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo site_url('welcome'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
    </body>
</html>
