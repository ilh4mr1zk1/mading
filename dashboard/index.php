<?php  

	require_once "../dbconfig.php";

    error_reporting();

	// Cek status login user jika tidak ada session
    if (!$user->isLoggedIn()) {  
        header("location:/mading"); //Redirect ke halaman login  
    }  

	$nama_user = $_SESSION['nama_user'];
  $name_role = $_SESSION['name_role'];

	if (isset($_POST['logout'])) {
		session_destroy();
		header("location:/mading");
	}

  // status_approve = 1 (belum di approve)
  // status_approve = 2 (sudah di approve)
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
  <title>AdminLTE 2 | Fixed Layout</title>
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
      <span class="logo-lg"><b>Admin</b>LTE</span>
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

                <li class="header">You have <span class="ini_notif_bwh"></span> messages not approve</li>

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

                <li class="header">You have <span class="ini_notif_bwh"></span> messages not approve</li>

                <li>

                <ul class="menu" id="isi_pengumuman">
        
                </ul>

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

            <li class="dropdown notifications-menu">

              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bullhorn"></i>
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
                      <input type="" id="via_ann" name="" style="width: 25%; margin-left: 10px;">
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="title_ann">Title Announcement</label>
                  <input type="text" id="title_ann" class="form-control" placeholder="Title Announcement ...">
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

   <div class="modal fade" id="modal-default-all">
    <div class="modal-dialog">
      <div class="modal-content" style="width: 80%;margin-left: auto;margin-right: auto;">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> Announcement </h4>
            </center>
        </div>
        <div class="modal-body" style="height:315px;">
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
          <li> <a href=""> <i class="fa fa-dashboard"></i> Dashboard </a> </li>
          <li class="active"><a href="javascript:void(0);"><i class="fa fa-book"></i> <span>Announcement</span></a></li>
        <?php endif; ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="taro_konten">
    
    <?php include 'dashboard.php'; ?>

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
    const id              = document.querySelector("#id_ann")
    const from            = document.querySelector("#from_ann")
    const via             = document.querySelector("#via_ann")
    const title           = document.querySelector("#title_ann")
    const main            = document.querySelector("#main_ann")
    let countDataMessage  = `<?= $countDataMessage; ?>`
    console.log(role);

    const forms = document.querySelector("#forms")

    $("#id_ann").hide()
    $(".reason").hide()
    $("#cancel_not_approve").hide()
    $("#save_reason").hide()

    $(".show_data").click(function(){

      if (role === 'HRD') {

        $("#modal-default").modal('show')
        from.setAttribute("readonly", "")
        from.style.backgroundColor = '#eee'

        via.setAttribute("readonly", "")
        via.style.backgroundColor = '#eee'

        title.setAttribute("readonly", "")
        title.style.backgroundColor = '#eee'

        main.setAttribute("readonly", "")
        main.style.backgroundColor = '#eee'

        let dataId    = $(this).data('id')
        let dataNama  = $(this).data('nama')
        let dataTitle = $(this).data('judul')
        let dataIsi   = $(this).data('isi')

        $("#id_ann").val(dataId)
        $("#from_ann").val(dataNama)
        $("#via_ann").val("Admin")
        $("#title_ann").val(dataTitle)
        $("#main_ann").val(dataIsi)

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
        let dataNama  = $(this).data('nama')
        let dataTitle = $(this).data('judul')
        let dataIsi   = $(this).data('isi')
        $("#from_ann").val(dataNama)
        $("#via_ann").val("Admin")
        $("#title_ann").val(dataTitle)
        $("#main_ann").val(dataIsi)

      }

    })

    $("#cancel_not_approve").click(function(){
      $("#cancel_not_approve").hide()
      $("#save_reason").hide()
      $("#not_approve").show()
      $("#approve").show()
      $(".reason").hide()
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
        }
      })
    })

    $("#futer").click(function(e){
      e.preventDefault()
      $("#modal-default-all").modal('show')
    })

    const loadData = () => {

      setInterval(function(){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            let dataNotif = JSON.parse(this.responseText).jumlah_notif
            // console.log(`ini data notif ${dataNotif}`);
            if (dataNotif == 0) {
              $(".ini_notif").hide()
              document.querySelector(".ini_notif_bwh").innerHTML = dataNotif
            } else {
              $(".ini_notif").show()
              document.querySelector(".ini_notif").innerHTML = dataNotif
              document.querySelector(".ini_notif_bwh").innerHTML = dataNotif
            }
            $("#isi_pengumuman").html(JSON.parse(this.responseText).display_html)
            $(".all_data").html(JSON.parse(this.responseText).display_all_html)
            $(".show_data").click(function(e){
              e.preventDefault()
              let dataId    = $(this).data('id')
              let dataNama  = $(this).data('from')
              let dataTitle = $(this).data('title')
              let dataMain  = $(this).data('main')

              if (role === 'HRD') {

                $("#modal-default").modal('show')
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
                $("#main_ann").val(dataMain)

                $("#approve").click(function(e){
                  e.preventDefault()

                  Swal.fire({
                    title: "Approve",
                    icon: "success"
                  });

                  $("#close_approve").click()

                })

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
                let dataNama  = $(this).data('nama')
                let dataTitle = $(this).data('judul')
                let dataIsi   = $(this).data('isi')
                $("#from_ann").val(dataNama)
                $("#via_ann").val("Admin")
                $("#title_ann").val(dataTitle)
                $("#main_ann").val(dataIsi)

              }

            })


            $(".ksg").click(function(e){
              let getNama = $(this).data('from')
              let dataId    = $(this).data('id')
              let dataNama  = $(this).data('from')
              let dataTitle = $(this).data('title')
              let dataMain  = $(this).data('main')

              if (role === 'HRD') {

                $("#modal-default").modal('show')
                $("#modal-default-all").modal('hide')
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
                $("#main_ann").val(dataMain)

                $("#approve").click(function(e){
                  e.preventDefault()

                  Swal.fire({
                    title: "Approve",
                    icon: "success"
                  });

                  $("#close_approve").click()

                })

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

    loadData()

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
