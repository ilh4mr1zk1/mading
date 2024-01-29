<?php  

	require_once "../dbconfig.php";
  // require 'data.php';

    error_reporting();

	// Cek status login user jika tidak ada session
    if (!$user->isLoggedIn()) {  
        header("location:/mading"); //Redirect ke halaman login  
    }  

	$nama_user = $_SESSION['nama_user'];
  $name_role = $_SESSION['name_role'];
  $user_id   = $_SESSION['user_id'];

	if (isset($_POST['logout'])) {
		session_destroy();
		header("location:/mading");
	}

  // status_approve = 1 (belum di approve)
  // status_approve = 2 (approve)
  // status_approve = 3 (tidak di approve) 

  $countDataMessage = $user->countDataMessage();

  // foreach ($getDataMessage as $row) {
  //   echo $row["message_id"] . " - " . $row["judul_pesan"] . " - " . $row["nama_user"] . "<br/>";
  // }
  // print_r($getDataMessage);
  // echo $getDataMessage;

  $server   = "localhost";
  $username = "root";
  $password = "";
  $database = "mading_practice";

  $koneksi  = mysqli_connect($server, $username, $password, $database);

  $queryDataMessage = "
    SELECT message.id as message_id, 
    message.message_title as judul_pesan,
    message.message_info as isi_pesan, 
    message.status_approve as status_approve, 
    message.user_id as user_id,
    users.id as id_users,
    users.nama_user as nama_user, 
    users.email as email 
    FROM message 
    WHERE 
    LEFT JOIN users
    ON message.user_id = users.id
  ";

  // $getDataMessage = mysqli_query($koneksi, $queryDataMessage);

?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Mading School </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
    
    #futer:hover {
      background-color: lightgrey;
    }

    #gambar {
      height: 100%;
      width: 100%;
      margin-top: 10px;
      object-fit: cover;
      background: #dfdfdf;
    }

    #modal-content-semua {
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }

    .container .popup-image {
      position: fixed;
      top: 0; left: 100px;
      background: rgba(0, 0, 0, 0.9);
      height: 100%;
      width: 100%;
      z-index: 100;
      margin-top: 30px;
      display: none;
    }

    .container .popup-image span {
      position: absolute;
      top: 0; right: 150px;
      font-size: 60px;
      font-weight: bolder;
      color: #fff;
      cursor: pointer;
      z-index: 100;
    }

    .container .popup-image img {
      position: absolute;
      top: 50%; left: 50%;
      height: 80%;
      transform: translate(-50%, -50%) scale(1);
      border: 5px solid #fff;
      border-radius: 5px;
      width: 750px;
      object-fit: cover;
    }

    .pad {
      padding: 10px;
      margin-left: auto;
      margin-right: auto;
    }

    @media (max-width:768px) {

      .bnnr {
        margin-left: auto;
        margin-right: auto;
      }

      #modal-content-semua {
        margin-top: 30%;
        width: 80%;
        margin-left: auto;
        margin-right: auto;
      }

      #modal-content-semua #modal-body-semua {
        padding: 10px;
      }

      .container .popup-image {
        position: fixed;
        top: 2%; left: 0;
        background: rgba(0, 0, 0, 0.9);
        height: 100%;
        width: 100%;
        z-index: 100;
        margin-top: 30px;
        display: none;
      }

      .container .popup-image span {
        position: absolute;
        top: 8%; right: 21px;
        font-size: 35px;
        font-weight: bolder;
        color: #fff;
        cursor: pointer;
        z-index: 100;
      }

      .container .popup-image img {
        position: absolute;
        top: 50%; left: 50%;
        height: auto;
        transform: translate(-50%, -50%) scale(1);
        border: 5px solid #fff;
        border-radius: 5px;
        width: 90%;
        object-fit: cover;
      }
    }

    /*#taro_isi_dashboard .portfolio {
      opacity: 0;
      transform: translate(0, -40px);
      transition: .5s;
    }

    #taro_isi_dashboard .portfolio.muncul {
      opacity: 1;
      transform: translate(0, 0);
    }*/

    .portfolio {
      cursor: pointer;
    }

  </style>
  
</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition skin-blue fixed sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="../index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Mading</b>School</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <?php if ($name_role == "HRD"): ?>

            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-success ini_notif" id="count_message"> </span>
              </a>
              <ul class="dropdown-menu">

                <li class="header">You have <span class="ini_notif_bwh_all"></span> messages not yet approve</li>

                <li>

                <ul class="menu" id="isi_pengumuman">
                  
                  <!-- <?php foreach ($getDataMessage as $data): ?>
                    <li class="show_data" data-toggle="modal" data-id="<?= $data['message_id']; ?>" data-target="modal-default" data-from="<?= $data['nama_user']; ?>"
                      data-judul="<?= $data['judul_pesan']; ?>" data-isi="<?= $data['isi_pesan']; ?>" data-nama="<?= $data['nama_user']; ?>">
                        <a href="#">
                          <div class="pull-left">
                            <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            <p style="font-size:13px;"> From : <?= $data['nama_user']; ?> </p>
                            <p style="font-size:13px;"> Via<span style="margin-left: 11px;"></span>: Admin </p>
                          </h4>
                          <h4> <?= $data['isi_pesan']; ?> </h4>
                        </a>
                    </li>
                  <?php endforeach; ?> -->

                </ul>

                <li class="footer" style="cursor: pointer;">
                  <a href="" id="futer"> See All Messages </a>
                </li>

              </ul>
            </li>

            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-thumbs-o-up"></i>
                <span class="label label-warning">10</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 10 notifications</li>
                <li>

                  <ul class="menu">
                    <li>
                      <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                      </a>
                    </li>
                  </ul>

                </li>
                <li class="footer"><a href="#">View all</a></li>
              </ul>
            </li>

            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-thumbs-o-down"></i>
                <span class="label label-warning">10</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 10 notifications</li>
                <li>

                  <ul class="menu">
                    <li>
                      <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                      </a>
                    </li>
                  </ul>

                </li>
                <li class="footer"><a href="#">View all</a></li>
              </ul>
            </li>

          <?php else: ?>

            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-success ini_notif" id="count_message"> </span>
              </a>
              <ul class="dropdown-menu">

                <li class="header">You have <span class="ini_notif_bwh_all"></span> messages not approve</li>

                <li>

                <ul class="menu" id="isi_pengumuman">
        
                </ul>

              </ul>
            </li>

          <?php endif; ?>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../dist/img/defaults.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?= $nama_user; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../dist/img/defaults.jpg" class="img-circle" alt="User Image">

                <p>
                  <?= $nama_user; ?> - <?= $name_role; ?>
                  <small>Member since Nov. 2012</small>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Edit Profile</a>
                </div>
                <div class="pull-right">
                  <form method="post">
                      <button type="submit" name="logout" class="btn btn-default btn-flat">Sign out</button>
                  </form>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>

  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> Announcement </h4>
            </center>
        </div>
        <div class="modal-body" style="margin-bottom: 10px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> From </label>
                      <input type="" name="" id="from_ann" style="width: 60%; margin-left: 10px;">
                      <input type="" name="" id="id_ann" style="width: 60%; margin-left: 10px;">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Via </label>
                      <input type="" id="via_ann" name="" style="width: 25%; margin-left: 23.5px;">
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="title_ann">Title Announcement</label>
                  <input type="text" id="title_ann" class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner">
                  <label for="banner"> Banner </label>
                  <!-- <input type="text" id="banner" name=""> -->
                  <img class="img-responsive pad" id="banner" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann">Announcement</label>
                  <textarea style="height: 150px;" class="form-control" id="main_ann" rows="3" placeholder="Announcement ..."></textarea>
                </div>

                <div class="form-group reason">
                  <label for="reason">Reason (Optional)</label>
                  <input type="text" id="reason" class="form-control" placeholder="Reason ... (Optional)">
                </div>

              </form>

            </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" id="not_approve" class="btn btn-danger">Not approved</button>
          <button type="button" id="cancel_not_approve" class="btn btn-primary">Cancel</button>
          <button type="button" id="approve" class="btn btn-success">Approve</button>
          <button type="button" id="save_reason" class="btn btn-danger">Not Approved</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="modal-default-see">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" id="tutup_see" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> Announcement </h4>
            </center>
        </div>
        <div class="modal-body" style="margin-bottom: 10px; height: 450px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px; overflow-y: scroll; height: 425px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> From </label>
                      <input type="" name="" id="from_ann_see" style="width: 60%; margin-left: 10px;">
                      <input type="" name="" id="id_ann_see" style="width: 60%; margin-left: 10px;">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Via </label>
                      <input type="" id="via_ann_see" name="" style="width: 25%; margin-left: 23.4px;">
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="title_ann_see">Title Announcement</label>
                  <input type="text" id="title_ann_see" class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner_see">
                  <label for="banner_see"> Banner </label>
                  <img class="img-responsive pad" id="banner_see" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann_see">Announcement</label>
                  <textarea style="height: 150px;" class="form-control" id="main_ann_see" rows="3" placeholder="Announcement ..."></textarea>
                </div>

                <div class="form-group reason_see">
                  <label for="reason">Reason (Optional)</label>
                  <input type="text" id="reason_see" class="form-control" placeholder="Reason ... (Optional)">
                </div>

              </form>

            </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_approve_see" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" id="not_approve_see" class="btn btn-danger">Not approved</button>
          <button type="button" id="cancel_not_approve_see" class="btn btn-primary">Cancel</button>
          <button type="button" id="approve_see" class="btn btn-success">Approve</button>
          <button type="button" id="save_reason_see" class="btn btn-danger">Not Approved</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-default-all">
    <div class="modal-dialog">
      <div class="modal-content" id="modal-content-semua">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> Announcement </h4>
            </center>
        </div>
        <div class="modal-body" id="modal-body-semua" style="height:315px;">
          <!-- <ul class="menu" id="isi_pengumumans">
          </ul> -->
          <section class="content" style="overflow-y: scroll; height: 300px;">

            <!-- Default box -->
            <div class="box all_data" style="cursor: pointer;">
              
            </div>
            <!-- /.box -->

          </section>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../dist/img/defaults.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $nama_user; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <?php if ($name_role == 'HRD'): ?>
          <li class="active"> <a href=""> <i class="fa fa-dashboard"></i> Dashboard </a> </li>
        <?php else: ?>
          <li class="active" id="side_bar_dashboard"> <a href=""> <i class="fa fa-dashboard"></i> Dashboard </a> </li>
          <li id="side_bar_announcement"><a href="javascript:void(0);"><i class="fa fa-book"></i> <span>Announcement</span></a></li>
        <?php endif; ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper" id="taro_konten">
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.18
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<script type="text/javascript">

  $(document).ready( function () {
    
    let role              = `<?= $name_role; ?>` 
    
    const user_id           = `<?= $user_id; ?>`;
    
    const id              = document.querySelector("#id_ann")
    const from            = document.querySelector("#from_ann")
    const via             = document.querySelector("#via_ann")
    const title           = document.querySelector("#title_ann")
    const main            = document.querySelector("#main_ann")

    const id_see          = document.querySelector("#id_ann_see")
    const from_see        = document.querySelector("#from_ann_see")
    const via_see         = document.querySelector("#via_ann_see")
    const title_see       = document.querySelector("#title_ann_see")
    const main_see        = document.querySelector("#main_ann_see")

    let countDataMessage  = `<?= $countDataMessage; ?>`
    console.log(role);

    const forms = document.querySelector("#forms")

    $("#id_ann").hide()
    $("#id_ann_see").hide()
    $(".reason").hide()
    $(".reason_see").hide()
    $("#cancel_not_approve").hide()
    $("#cancel_not_approve_see").hide()
    $("#save_reason").hide()
    $("#save_reason_see").hide()
    $("#approve").hide()
    $("#approve_see").hide() 
    $("#not_approve_see").hide()

    let klikAll = 0;

    $("#cancel_not_approve").click(function(){
      $("#cancel_not_approve").hide()
      $("#save_reason").hide()
      $("#not_approve").show()
      $("#approve").show()
      $(".reason").hide()
    })

    $("#cancel_not_approve_see").click(function(e){
      e.preventDefault()
      $("#modal-default-all").modal("hide")
      $("#save_reason_see").hide()
      $("#not_approve_see").show()
      $("#cancel_not_approve_see").hide()
      $("#approve_see").show()
      $(".reason_see").hide()
    })

    $("#save_reason").click(function(e){
      e.preventDefault()

      let dataId    = document.querySelector("#id_ann").value
      let reason    = document.querySelector("#reason").value

      $.ajax({
        url   : "data.php",
        type  : "POST",
        data  : {
          message_title  : title.value,
          message_info   : main.value,
          status_approve : 3,
          id             : dataId,
          reason         : reason
        },
        success:function(data) {
          console.log(JSON.parse(data));
          $("#close_approve").click()
          $("#reason").val("")
          Swal.fire({
            icon  : "error",
            title : "Not Approve",
            timer : 1000
          });
        }
      })
    })

    $("#save_reason_see").click(function(e){
      e.preventDefault()

      let dataIdSee = document.querySelector("#id_ann_see").value
      let reasonSee = document.querySelector("#reason_see").value

      $.ajax({
        url   : "data.php",
        type  : "POST",
        data  : {
          message_title  : title_see.value,
          message_info   : main_see.value,
          status_approve : 3,
          id             : dataIdSee,
          reason         : reasonSee
        },
        success:function(data) {
          console.log(JSON.parse(data));
          $("#close_approve_see").click()
          $("#reason_see").val("")
          Swal.fire({
            icon: "error",
            title: "Not Approve"
          });
        }
      })
    })

    $("#approve").click(function(e){
      e.preventDefault()
      let dataId    = document.querySelector("#id_ann").value

      $.ajax({
        url  : "data.php",
        type : "POST",
        data : {
          message_title  : title.value,
          message_info   : main.value,
          status_approve : 2,
          id             : dataId,
          reason         : 0
        },
        success:function(data) {

          $("#id_ann").val("")
          $("#from_ann").val("")
          $("#via_ann").val("")
          $("#title_ann").val("")
          $("#main_ann").val("")

          console.log(JSON.parse(data));
          Swal.fire({
            title : "Approve",
            icon  : "success",
            timer : 1000
          });

          $("#close_approve").click()

        }

      })

    })

    $("#approve_see").click(function(e){
      e.preventDefault()
      let dataIdSee    = document.querySelector("#id_ann_see").value

      $.ajax({
        url  : "data.php",
        type : "POST",
        data : {

          message_title  : title_see.value,
          message_info   : main_see.value,
          status_approve : 2,
          id             : dataIdSee,
          reason         : 0
        },
        success:function(data) {

          $("#id_ann_see").val("")
          $("#from_ann_see").val("")
          $("#via_ann_see").val("")
          $("#title_ann_see").val("")
          $("#main_ann_see").val("")

          console.log(JSON.parse(data));
          Swal.fire({
            title: "Approve",
            icon: "success"
          });

          $("#close_approve_see").click()

        }

      })

    })

    $("#not_approve_see").click(function(e){

      $(".reason_see").show()
      $("#reason_see").focus()
      $("#not_approve_see").hide()
      $("#approve_see").hide()
      $("#cancel_not_approve_see").show()
      $("#save_reason_see").show()

    })

    $("#taro_konten").load("dashboard.php")

    const loadData = () => {

      setInterval(function(){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {

            let dataNotif    = JSON.parse(this.responseText).jumlah_notif
            let dataNotifHRD = JSON.parse(this.responseText).jumlah_notif_hrd
            console.log(JSON.parse(this.responseText).jumlah_approve);

            if (role === 'HRD') {
              $(".all_data").html(JSON.parse(this.responseText).display_all_html_hrd)
              if (dataNotifHRD == 0) {

                $(".ini_notif").hide()
                document.querySelector(".ini_notif_bwh_all").innerHTML = dataNotifHRD

              } else {

                $(".ini_notif").show()
                document.querySelector(".ini_notif").innerHTML = dataNotifHRD
                document.querySelector(".ini_notif_bwh_all").innerHTML = dataNotifHRD

              }

              $("#isi_pengumuman").html(JSON.parse(this.responseText).display_html_hrd)

            } else {

              if (dataNotif == 0) {

                $(".ini_notif").hide()
                document.querySelector(".ini_notif_bwh_all").innerHTML = dataNotif

              } else {

                $(".ini_notif").show()
                document.querySelector(".ini_notif").innerHTML = dataNotif
                document.querySelector(".ini_notif_bwh_all").innerHTML = dataNotif

              }

            }

            $("#futer").click(function(e){
              e.preventDefault()
              $("#modal-default-all").modal('show')
            })

            $(".show_data").click(function(e){
              e.preventDefault()
              const image = document.querySelector("img[id='banner']")
              let dataId    = $(this).data('id')
              let dataNama  = $(this).data('from')
              let dataTitle = $(this).data('title')
              let dataImage = $(this).data('img')
              let dataMain  = $(this).data('main')

              if (role === 'HRD') {
                
                $("#modal-default").modal('show')

                $("#id_ann").val("")
                $("#from_ann").val("")
                $("#via_ann").val("")
                $("#title_ann").val("")
                $("#main_ann").val("")

                from.setAttribute("readonly", "")
                from.style.backgroundColor = '#eee'

                via.setAttribute("readonly", "")
                via.style.backgroundColor = '#eee'

                title.setAttribute("readonly", "")
                title.style.backgroundColor = '#eee'

                main.setAttribute("readonly", "")
                main.style.backgroundColor = '#eee'

                $("#id_ann").val(dataId)
                $("#from_ann").val(dataNama)
                $("#via_ann").val("Admin")
                $("#title_ann").val(dataTitle)
                image.setAttribute("src", `../img/${dataImage}`)
                $("#main_ann").val(dataMain)

                $("#approve").show()                

                $("#not_approve").click(function(){
                  
                  $(".reason").show()
                  $("#reason").focus()
                  $("#cancel_not_approve").show()
                  $("#save_reason").show()
                  $("#not_approve").hide()
                  $("#approve").hide()

                })

              } else {

                $("#modal-default").modal('show')
                let dataFrom  = $(this).data('from')
                let dataTitle = $(this).data('title')
                let dataIsi   = $(this).data('main')
                $("#from_ann").val(dataFrom)
                $("#via_ann").val("Admin")
                $("#title_ann").val(dataTitle)
                $("#main_ann").val(dataIsi)

              }

            })

            $(".ksg").click(function(e){
              e.preventDefault()

              klikAll = 1;
              console.log(klikAll);
              let tempatBanner = document.querySelector("#banner_see")
              let getNama      = $(this).data('from')
              let dataId       = $(this).data('id')
              let dataNama     = $(this).data('from')
              let dataTitle    = $(this).data('title')
              let dataGambar   = $(this).data('gambar')
              let dataMain     = $(this).data('main')

              if (role === 'HRD') {

                $("#modal-default-see").modal('show')

                $("#id_ann_see").val("")
                $("#from_ann_see").val("")
                $("#via_ann_see").val("")
                $("#title_ann_see").val("")
                $("#main_ann_see").val("")

                // $("#modal-default-all").modal
                $("#modal-default-all").modal("hide")
                from_see.setAttribute("readonly", "")
                from_see.style.backgroundColor = '#eee'

                via_see.setAttribute("readonly", "")
                via_see.style.backgroundColor = '#eee'

                title_see.setAttribute("readonly", "")
                title_see.style.backgroundColor = '#eee'

                main_see.setAttribute("readonly", "")
                main_see.style.backgroundColor = '#eee'

                $("#id_ann_see").val(dataId)
                $("#from_ann_see").val(dataNama)
                $("#via_ann_see").val("Admin")
                $("#title_ann_see").val(dataTitle)
                tempatBanner.setAttribute("src", `../img/${dataGambar}`)
                $("#main_ann_see").val(dataMain)

                $("#not_approve_see").show()

                $("#approve_see").show()

              } else {

                $("#modal-default").modal('show')
                let dataNama  = $(this).data('nama')
                let dataTitle = $(this).data('judul')
                let dataIsi   = $(this).data('isi')
                $("#from_ann").val(dataNama)
                $("#via_ann").val("Admin")
                $("#title_ann").val(dataTitle)
                $("#main_ann").val(dataIsi)

              }
            })
          }
        };

        xhttp.open("GET", "data.php", true);
        xhttp.send();
      }, 1000)

    }

    $("#tutup_see").click(function(e){
      e.preventDefault()
      $("#modal-default-all").modal("show")
      $(".reason_see").hide()
      $("#approve_see").hide()
      $("#cancel_not_approve_see").hide()
      $("#save_reason_see").hide()
      $("#not_approve_see").show()
      $("#cancel_not_approve_see").hide()
      $("#approve_see").show()
    })

    $("#close_approve_see").click(function(e){
      e.preventDefault
      $("#modal-default-all").modal("show")
      $(".reason_see").hide()
      $("#approve_see").hide()
      $("#cancel_not_approve_see").hide()
      $("#save_reason_see").hide()
      $("#not_approve_see").show()
      $("#cancel_not_approve_see").hide()
      $("#approve_see").show()
    })

    loadData()

    // $(window).scroll(function() {
    //   var wScroll = $(this).scrollTop();
    //   if( wScroll > $('#taro_isi_dashboard').offset().top - 250 ) {
    //     $('#taro_isi_dashboard .portfolio').each(function(i) {
    //       setTimeout(function() {
    //         $('#taro_isi_dashboard .portfolio').eq(i).addClass('muncul');  
    //       }, 200 * (i+1));
    //     })

    //   }
    // })

    $('#side_bar_dashboard').click(function(e) {
      e.preventDefault();

      let dashboard = $(this).attr('id');

      if (dashboard == "side_bar_dashboard") {
        $("#side_bar_dashboard").addClass("active")
        $("#side_bar_announcement").removeClass("active")
        $('#taro_konten').load('dashboard.php')
      }

    });

    $('#side_bar_announcement').click(function(e) {
      e.preventDefault();

      let announcement = $(this).attr('id');

      if (announcement == "side_bar_announcement") {
        $("#side_bar_announcement").addClass("active")
        $("#side_bar_dashboard").removeClass("active")
        $('#taro_konten').load('announcement.php', {
          isi_title : "TEST",
          user_id   : user_id
        })
      }

    });

    let getElementReason    = document.querySelector(".reason")
    let buttonNotApprove    = document.querySelector("#not_approve")
    let elementModalContent = document.querySelector(".modal-content")

    document.addEventListener('click', function(e) {
      if ( !buttonNotApprove.contains(e.target) && !forms.contains(e.target) && !elementModalContent.contains(e.target) ) {

        $(".reason").hide()
        $("#cancel_not_approve").hide()
        $("#save_reason").hide()
        $("#not_approve").show()
        $("#approve").show()
        $("#reason").val("")
      
      } 
    })

  });
  
</script>

</body>
</html>
