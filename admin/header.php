


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $page_title; ?> - Admin Panel - Share Your Idea</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Date Dropper - Date Picker -->
        <link href="plugins/Datedropper3-master/datedropper.min.css" rel="stylesheet" type="text/css"/>
        <link href="plugins/timedropper-master/timedropper-master/timedropper.css" rel="stylesheet" type="text/css"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="plugins/ionicons.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="plugins/morris/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">


        <style>
            .skin-blue .sidebar-menu > li > a {
                font-size: 16px;
            }

            @media print {
                a[href]:after {
                    content: none !important;
                }
            }
            .last{
                padding-left: 15px;
                padding-right: 15px;
            }
            textarea{
                resize: none;
            }
            #msg_add_service{
                /*width: 60%;*/
                display: inline-block;
                /*float: right;*/
            }

            div.datedropper.primary .pick-lg-b .pick-sl:before, div.datedropper.primary .pick-lg-h, div.datedropper.primary .pick-m, div.datedropper.primary .pick-submit, div.datedropper.primary:before {
                background-color: black;
            }
            div.datedropper.primary .pick li span, div.datedropper.primary .pick-btn, div.datedropper.primary .pick-lg-b .pick-wke, div.datedropper.primary .pick-y.pick-jump {
                color: #3c8dbc;
            }
            .dis{
                color: #999 !important;
                cursor: not-allowed;
            }
            .label{
                font-size: 90%;
            }
            

            /*            .img-box {
                            margin-top: 25px;
                        }*/

        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="../index.php" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>Idea</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Share Your Idea</b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>



                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->


                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="hidden-xs">Welcome <strong><?php echo $_SESSION["username"]; ?></strong></span>

                                </a>

                            </li>
                            

                        </ul>
                        <a href="logout.php" class="btn btn-default btn-flat" style="margin: 8px;">Logout</a>
                    </div>
                </nav>
            </header>