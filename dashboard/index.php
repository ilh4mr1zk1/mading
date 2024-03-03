<?php  

	require_once "../dbconfig.php";

	// Cek status login user jika tidak ada session
  if (!$user->isLoggedIn()) { 
    header("location:/mading"); //Redirect ke halaman login  
  }

  $error = '';

  if ($_SESSION['nama_user'] != true) {
    $error = 1;
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

    #futer_approved:hover {
      background-color: lightgrey;
    }

    #futer_not_approved:hover {
      background-color: lightgrey;
    }

    #futer_waiting:hover {
      background-color: lightgrey;
    }

    /*.show_data_status_waiting:hover {
      background-color: #f4f4f4;
    }

    .show_data_status_waiting {
      cursor: pointer;
    }*/

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

    #modal-content-semua-status {
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }

    #modal-content-semua-status-not-approved {
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }

    #modal-content-semua-status-waiting {
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }

    .popup-image {
      overflow-y: scroll; 
      height: 100%;
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
      height: 90%;
      transform: translate(-50%, -50%) scale(1);
      border: 5px solid #fff;
      border-radius: 5px;
      width: auto;
      object-fit: contain;
    }

    .pad {
      padding: 10px;
      margin-left: auto;
      margin-right: auto;
    }

    #modal-body-semua {
      height: 420px;
    }

    #modal-body-semua-status {
      height: 420px;
    }

    #modal-body-semua-status-not-approved {
      height: 420px;
    }

    #modal-body-semua-status-waiting {
      height: 420px;
    }

    #content_all_body {
      height: 400px;
    }

    #content_all_body_status {
      height: 400px;
    }

    #content_all_body_status_not_approved {
      height: 400px;
    }

    #content_all_body_status_waiting {
      height: 400px;
    }

    .box {
      position: relative;
      border-radius: 3px;
      background: #ffffff;
      margin-bottom: 20px;
      width: 100%;
      border-top: 0;
      box-shadow: 0;
    }

    @media (min-width: 992px) {
      
      .timeApproved {
        width: 55%;
        margin-left: -5%;
      }

      .timeNotApproved {
        width: 60%;
        margin-left: -10%;
      }

    }

    @media (max-width:768px) {

      .bnnr {
        margin-left: auto;
        margin-right: auto;
      }

      #modal-content-semua {
        margin-top: 5%;
        width: 80%;
        margin-left: auto;
        margin-right: auto;
      }

      #modal-content-semua-status {
        margin-top: 5%;
        width: 80%;
        margin-left: auto;
        margin-right: auto;
      }

      #modal-content-semua-status-not-approved {
        margin-top: 5%;
        width: 80%;
        margin-left: auto;
        margin-right: auto;
      }

      #modal-content-semua #modal-body-semua {
        padding: 10px;
      }

      #modal-content-semua-status #modal-body-semua-status {
        padding: 10px;
      }

      #modal-content-semua-status-not-approved #modal-body-semua-status-not-approved {
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
        height: 60%;
        transform: translate(-50%, -50%) scale(1);
        border: 5px solid #fff;
        border-radius: 5px;
        width: 90%;
        object-fit: contain;
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
                <span class="label label-success ini_notif_hrd" id="count_message_hrd"> </span>
              </a>
              <ul class="dropdown-menu">

                <li class="header">You have <span class="ini_notif_bwh_all_hrd"></span> messages not yet approve</li>

                <li>

                <ul class="menu" id="isi_pengumuman_hrd">
                  
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
                <i class="fa fa-fw fa-thumbs-o-up"></i>
                <span class="label label-success notif_setuju"> </span>
              </a>
              <ul class="dropdown-menu">

                <li class="header">You have <span class="ini_notif_setuju_bwh_all"></span> notice was approved </li>

                <li>

                <ul class="menu" id="isi_pengumuman_approved">
        
                </ul>

                <li class="footer" id="futer_approved" style="cursor: pointer;">
                  <a href="" id="futer_approved"> See All Messages </a>
                </li>

              </ul>
            </li>

            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-fw fa-thumbs-o-down"></i>
                <span class="label label-success notif_tidak_disetujui"> </span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have <span class="ini_notif_tidak_disetujui_bwh_all"></span> announcement not approved </li>

                <li>

                <ul class="menu" id="isi_pengumuman_not_approved">
        
                </ul>

                <li class="footer" id="futer_not_approved" style="cursor: pointer;">
                  <a href="" id="futer_not_approved"> See All Messages </a>
                </li>

              </ul>
            </li>

            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-hourglass-2"></i>
                <span class="label label-success notif_waiting_confirm">  </span>
              </a>
              <ul class="dropdown-menu">

                <li class="header">Waiting <span class="ini_notif_waiting_bwh_all"> 3 </span> Confirmation Announcement!</li>

                <li>

                <ul class="menu" id="isi_pengumuman_waiting">
        
                </ul>

                <li class="footer" id="futer_waiting" style="cursor: pointer;">
                  <a href="" id="futer_waiting"> See All Messages </a>
                </li>

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

  <!-- Modal -->
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
                      <label> Date Posted </label>
                      <input type="" id="via_ann" name="" style="width: 61%; margin-left: 5.5px;">
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
                      <label> Date Posted </label>
                      <input type="" id="via_ann_see" name="" style="width: 61%; margin-left: 5.5px;">
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

  <div class="modal fade" tabindex="-1" role="dialog" id="modal-default-see-status">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" id="tutup_see_status" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> Announcement Approved </strong> </h4>
            </center>
        </div>
        <div class="modal-body" style="margin-bottom: 10px; height: 450px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px; overflow-y: scroll; height: 425px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Status : <strong style="color: green;"> Approved </strong> <i class="fa fa-fw fa-check" style="color: yellowgreen;"></i> </label>
                    </div>
                  </div>

                  <div class="col-md-6 timeApproved">
                    <div class="form-group">
                      <label> Time Approved : <strong id="time_approved_status">  </strong> </label>
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="title_ann_see_status">Title Announcement</label>
                  <input type="text" id="title_ann_see_status" class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner_see">
                  <label for="banner_see_status"> Banner </label>
                  <img class="img-responsive pad" id="banner_see_status" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann_see_status">Announcement</label>
                  <textarea style="height: 150px;" class="form-control" id="main_ann_see_status" rows="3" placeholder="Announcement ..."></textarea>
                </div>

                <div class="form-group reason_see">
                  <label for="reason">Reason (Optional)</label>
                  <input type="text" id="reason_see" class="form-control" placeholder="Reason ... (Optional)">
                </div>

              </form>

            </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_approve_see_status" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" id="cancel_not_approve_see_status" class="btn btn-primary">Edit</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="modal-default-see-status-not-approved">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" id="tutup_see_status_not_approved" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> Announcement Not Approved </strong> </h4>
            </center>
        </div>
        <div class="modal-body" style="margin-bottom: 10px; height: 450px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px; overflow-y: scroll; height: 425px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Status : <strong style="color: red;"> Not Approved </strong> <i class="fa fa-fw fa-times" style="color: red;"></i> </label>
                    </div>
                  </div>

                  <div class="col-md-6 timeNotApproved">
                    <div class="form-group">
                      <label style="width: 100%;"> Time Not Approved : <strong id="time_not_approved_status">  </strong> </label>
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="reason_see_not_approved">Reason not approved</label>
                  <input type="text" id="reason_see_not_approved" readonly class="form-control" placeholder="Reason ... (Optional)">
                </div>

                <div class="form-group">
                  <label for="title_ann_see_status_not_approved">Title Announcement</label>
                  <input type="text" id="title_ann_see_status_not_approved" class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner_see">
                  <label for="banner_see_status_not_approved"> Banner </label>
                  <img class="img-responsive pad" id="banner_see_status_not_approved" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann_see_status_not_approved">Announcement</label>
                  <textarea style="height: 150px;" class="form-control" id="main_ann_see_status_not_approved" rows="3" placeholder="Announcement ..."></textarea>
                </div>

              </form>

            </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_see_status_not_approved" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" id="cancel_not_approve_see_status_not_approved" class="btn btn-primary">Edit</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="modal-default-see-status-waiting">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" id="tutup_see_status_waiting" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> Announcement Approved </strong> </h4>
            </center>
        </div>
        <div class="modal-body" style="margin-bottom: 10px; height: 450px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px; overflow-y: scroll; height: 425px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Status : <strong style="color: green;"> Waiting </strong> <i class="fa fa-fw fa-hourglass-half" style="color: green;"></i> </label>
                    </div>
                  </div>

                  <div class="col-md-6 timeApproved">
                    <div class="form-group">
                      <label> Date Posted : <strong id="time_status_waiting">  </strong> </label>
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="title_ann_see_status_waiting">Title Announcement</label>
                  <input type="text" id="title_ann_see_status_waiting" class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner_see">
                  <label for="banner_see_status_waiting"> Banner </label>
                  <img class="img-responsive pad" id="banner_see_status_waiting" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann_see_status_waiting">Announcement</label>
                  <textarea style="height: 150px;" class="form-control" id="main_ann_see_status_waiting" rows="3" placeholder="Announcement ..."></textarea>
                </div>

              </form>

            </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_see_status_waiting" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" id="edit_see_status_waiting" class="btn btn-primary">Edit</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-default-status-approve">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> Announcement Approved </strong> </h4>
            </center>
        </div>

        <div class="modal-body" style="margin-bottom: 10px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Status : <strong style="color: yellowgreen;"> Approved </strong> <i class="fa fa-fw fa-check" style="color: yellowgreen;"></i> </label>
                    </div>
                  </div>

                  <div class="col-md-6 timeApproved">
                    <div class="form-group">
                      <label> Time Approved : <strong id="time_approved">  </strong> </label>
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="title_ann_approved">Title Announcement</label>
                  <input type="text" id="title_ann_approved" readonly class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner">
                  <label for="banner_approved"> Banner </label>
                  <img class="img-responsive pad" id="banner_approved" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann_approved">Announcement</label>
                  <textarea style="height: 150px;" readonly class="form-control" id="main_ann_approved" rows="3" placeholder="Announcement ..."></textarea>
                </div>

              </form>

            </div>

        </div>

        <div class="modal-footer">
          <button type="button" id="close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        </div>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-default-status-not-approve">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> Announcement Not Approved </strong> </h4>
            </center>
        </div>

        <div class="modal-body" style="margin-bottom: 10px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Status : <strong style="color: red;"> Not Approved </strong> <i class="fa fa-fw fa-times" style="color: red;"></i> </label>
                    </div>
                  </div>

                  <div class="col-md-6 timeNotApproved">
                    <div class="form-group">
                      <label style="width: 100%;"> Time Not Approved : <strong id="time_not_approved">  </strong> </label>
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="reason_not_approved">Reason not approved</label>
                  <input type="text" id="reason_not_approved" readonly class="form-control" placeholder="Reason ...">
                </div>

                <div class="form-group">
                  <label for="title_ann_not_approved">Title Announcement</label>
                  <input type="text" id="title_ann_not_approved" readonly class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner">
                  <label for="banner_not_approved"> Banner </label>
                  <img class="img-responsive pad" id="banner_not_approved" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann_not_approved">Announcement</label>
                  <textarea style="height: 150px;" readonly class="form-control" id="main_ann_not_approved" rows="3" placeholder="Announcement ..."></textarea>
                </div>

              </form>

            </div>

        </div>

        <div class="modal-footer">
          <button type="button" id="close_not_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" id="edit_not_approve" class="btn btn-primary pull-right" data-dismiss="modal">Edit</button>
        </div>

      </div>
      <!-- /.modal-content -->
    </div>
  </div>

  <div class="modal fade" id="modal-default-status-waiting">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> Waiting Confirm Announcement </strong> </h4>
            </center>
        </div>

        <div class="modal-body" style="margin-bottom: 10px;">

            <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

              <form role="form" id="forms">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Status : <strong style="color: green;"> Waiting </strong> <i class="fa fa-fw fa-hourglass-half" style="color: green;"></i> </label>
                    </div>
                  </div>

                  <div class="col-md-6 timeNotApproved">
                    <div class="form-group">
                      <label> Time Posted : <strong id="time_posted">  </strong> </label>
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="title_ann_waiting">Title Announcement</label>
                  <input type="text" id="title_ann_waiting" readonly class="form-control" placeholder="Title Announcement ...">
                </div>

                <div class="form-group gambar_banner">
                  <label for="banner_waiting"> Banner </label>
                  <img class="img-responsive pad" id="banner_waiting" alt="Photo">
                </div>

                <div class="form-group">
                  <label for="main_ann_waiting">Announcement</label>
                  <textarea style="height: 150px;" readonly class="form-control" id="main_ann_waiting" rows="3" placeholder="Announcement ..."></textarea>
                </div>

              </form>

            </div>

        </div>

        <div class="modal-footer">
          <button type="button" id="close_waiting" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" id="edit_waiting" class="btn btn-primary pull-right" data-dismiss="modal">Edit</button>
        </div>

      </div>
      <!-- /.modal-content -->
    </div>
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
        <div class="modal-body" id="modal-body-semua">
          <!-- <ul class="menu" id="isi_pengumumans">
          </ul> -->
          <section class="content" id="content_all_body" style="overflow-y: scroll;">

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

  <div class="modal fade" id="modal-default-all-status-approved">
    <div class="modal-dialog">
      <div class="modal-content" id="modal-content-semua-status">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> All Announcement Approved </strong> </h4>
            </center>
        </div>
        <div class="modal-body" id="modal-body-semua-status">
          <!-- <ul class="menu" id="isi_pengumumans">
          </ul> -->
          <section class="content" id="content_all_body_status" style="overflow-y: scroll;">

            <!-- Default box -->
            <div class="box all_data_status_approve" style="cursor: pointer; padding: 10px;">
              
            </div>
            <!-- /.box -->

          </section>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_approve_status" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-default-all-status-not-approved">
    <div class="modal-dialog">
      <div class="modal-content" id="modal-content-semua-status-not-approved">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> All Announcement Not Approved </strong> </h4>
            </center>
        </div>
        <div class="modal-body" id="modal-body-semua-status-not-approved">
          <!-- <ul class="menu" id="isi_pengumumans">
          </ul> -->
          <section class="content" id="content_all_body_status_not_approved" style="overflow-y: scroll;">

            <!-- Default box -->
            <div class="box all_data_status_not_approve" style="cursor: pointer; padding: 10px;">
              
            </div>
            <!-- /.box -->

          </section>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_approve_status_not_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-default-all-waiting">
    <div class="modal-dialog">
      <div class="modal-content" id="modal-content-semua-status-waiting">
        <div class="modal-header" style="border-bottom-color: white;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <center>
              <h4 class="modal-title"> <strong> All Announcement Waiting Confirm </strong> </h4>
            </center>
        </div>
        <div class="modal-body" id="modal-body-semua-status-waiting">
          <!-- <ul class="menu" id="isi_pengumumans">
          </ul> -->
          <section class="content" id="content_all_body_status_waiting" style="overflow-y: scroll;">

            <!-- Default box -->
            <div class="box all_data_status_waiting" style="cursor: pointer; padding: 10px;">
              
            </div>
            <!-- /.box -->

          </section>

        </div>
        <div class="modal-footer">
          <button type="button" id="close_status_waiting" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
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
    
    const error           = `<?= $error; ?>`;
    const user_id         = `<?= $user_id; ?>`;
    
    const id                            = document.querySelector("#id_ann")
    const from                          = document.querySelector("#from_ann")
    const via                           = document.querySelector("#via_ann")
    const title                         = document.querySelector("#title_ann")
    const main                          = document.querySelector("#main_ann")

    const id_see                        = document.querySelector("#id_ann_see")
    const from_see                      = document.querySelector("#from_ann_see")
    const via_see                       = document.querySelector("#via_ann_see")
    const title_see                     = document.querySelector("#title_ann_see")
    const main_see                      = document.querySelector("#main_ann_see")

    const title_see_status              = document.querySelector("#title_ann_see_status");
    const main_see_status               = document.querySelector("#main_ann_see_status");

    const title_see_status_not_approved = document.querySelector("#title_ann_see_status_not_approved");
    const main_see_status_not_approved  = document.querySelector("#main_ann_see_status_not_approved");

    const title_see_status_waiting      = document.querySelector("#title_ann_see_status_waiting");
    const main_see_status_waiting       = document.querySelector("#main_ann_see_status_waiting");

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

            let dataNotifHRD              = JSON.parse(this.responseText).jumlah_notif_hrd;

            let dataNotifApproveById      = JSON.parse(this.responseText).jumlah_approve_by_id;
            let dataNotifNotApproveById   = JSON.parse(this.responseText).jumlah_not_approve_by_id;
            let dataNotifWaitingResponse  = JSON.parse(this.responseText).count_waiting_response;

            // console.log(JSON.parse(this.responseText).all_data);

            if (role === 'HRD') {
              $(".all_data").html(JSON.parse(this.responseText).display_all_html_hrd);
              $("#isi_pengumuman_hrd").html(JSON.parse(this.responseText).display_html_hrd);
              document.querySelector(".ini_notif_bwh_all_hrd").innerHTML = dataNotifHRD;
              
              if (dataNotifHRD == 0) {

                $(".ini_notif_hrd").hide();

              } else {

                $(".ini_notif_hrd").show();
                document.querySelector(".ini_notif_hrd").innerHTML  = dataNotifHRD;

              }

            } else {

              $(".all_data_status_approve").html(JSON.parse(this.responseText).display_all_html_approve);
              $(".all_data_status_not_approve").html(JSON.parse(this.responseText).display_all_html_not_approve);
              $(".all_data_status_waiting").html(JSON.parse(this.responseText).display_html_waiting);

              $("#isi_pengumuman_approved").html(JSON.parse(this.responseText).display_html_short_approve);
              $("#isi_pengumuman_not_approved").html(JSON.parse(this.responseText).display_html_short_not_approve);
              $("#isi_pengumuman_waiting").html(JSON.parse(this.responseText).display_html_short_waiting);

              if (dataNotifApproveById == 0) {

                $(".notif_setuju").hide();
                document.querySelector(".ini_notif_setuju_bwh_all").innerHTML = dataNotifApproveById;

              } else {

                $(".notif_setuju").show();
                document.querySelector(".notif_setuju").innerHTML             = dataNotifApproveById;
                document.querySelector(".ini_notif_setuju_bwh_all").innerHTML = dataNotifApproveById;

              }

              if (dataNotifNotApproveById == 0) {

                $(".notif_tidak_disetujui").hide();
                document.querySelector(".ini_notif_tidak_disetujui_bwh_all").innerHTML = dataNotifNotApproveById;

              } else {

                $(".notif_tidak_disetujui").show();
                $(".notif_tidak_disetujui").html(dataNotifNotApproveById);
                document.querySelector(".ini_notif_tidak_disetujui_bwh_all").innerHTML  = dataNotifNotApproveById;

              }

              if (dataNotifWaitingResponse == 0) {

                $(".notif_waiting_confirm").hide();
                document.querySelector(".ini_notif_waiting_bwh_all").innerHTML = dataNotifWaitingResponse;

              } else {

                $(".notif_waiting_confirm").show();
                $(".notif_waiting_confirm").html(dataNotifWaitingResponse);
                document.querySelector(".ini_notif_waiting_bwh_all").innerHTML = dataNotifWaitingResponse;

              }

            }

            $("#futer").click(function(e){
              e.preventDefault();
              $("#modal-default-all").modal('show')
            })


            if (dataNotifApproveById < 6 ) {

              $("#futer_approved").hide();

            } else {

              $("#futer_approved").show();
              $("#futer_approved").click(function(e) {
                e.preventDefault();
                $("#modal-default-all-status-approved").modal('show');
              })

            }

            if (dataNotifNotApproveById < 6) {

              $("#futer_not_approved").hide();

            } else {

              $("#futer_not_approved").show();
              $("#futer_not_approved").click(function(e) {
                e.preventDefault();
                $("#modal-default-all-status-not-approved").modal('show');
                // $("#modal-default-all-status-approved").modal('show');
              })

            }

            if (dataNotifWaitingResponse < 6) {

              $("#futer_waiting").hide();

            } else {

              $("#futer_waiting").show();
              $("#futer_waiting").click(function(e) {
                e.preventDefault();
                $("#modal-default-all-waiting").modal('show');
              })

            }

            $(".show_data").click(function(e){
              e.preventDefault();

              const image = document.querySelector("img[id='banner']")
              let dataId     = $(this).data('id')
              let dataNama   = $(this).data('from')
              let dataPosted = $(this).data('time_posted')
              let dataTitle  = $(this).data('title')
              let dataImage  = $(this).data('img')
              let dataMain   = $(this).data('main')

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
                $("#via_ann").val(dataPosted)
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

            $(".show_data_status_approve").click(function(e) {
              e.preventDefault();

              const image   = document.querySelector("img[id='banner_approved']")
              let timeApproved  = $(this).data('time_approved')
              let dataId        = $(this).data('id')
              let dataTitle     = $(this).data('title')
              let dataImage     = $(this).data('img')
              let dataMain      = $(this).data('main')

              $("#modal-default-status-approve").modal('show')

              $("#title_ann_approved").val(dataTitle)
              image.setAttribute("src", `../img/${dataImage}`)
              $("#main_ann_approved").val(dataMain)
              $("#time_approved").html(timeApproved)

            })

            $(".show_data_status_not_approve").click(function(e) {

              e.preventDefault();

              const image           = document.querySelector("img[id='banner_not_approved']")
              let timeNotApproved   = $(this).data('time_not_approved')
              let dataId            = $(this).data('id')
              let dataTitle         = $(this).data('title')
              let dataReason        = $(this).data('reason')
              let dataImage         = $(this).data('img')
              let dataMain          = $(this).data('main')

              $("#modal-default-status-not-approve").modal('show')

              $("#reason_not_approved").val(dataReason)
              $("#title_ann_not_approved").val(dataTitle)
              image.setAttribute("src", `../img/${dataImage}`)
              $("#main_ann_not_approved").val(dataMain)
              $("#time_not_approved").html(timeNotApproved)

            })

            $(".show_data_status_waiting").click(function(e) {

              e.preventDefault();

              const image           = document.querySelector("img[id='banner_waiting']")
              let timeNotApproved   = $(this).data('time_not_approved')
              let dataId            = $(this).data('id')
              let dataPosted        = $(this).data('tgl_buat')
              let dataTitle         = $(this).data('title')
              let dataReason        = $(this).data('reason')
              let dataImage         = $(this).data('img')
              let dataMain          = $(this).data('main')

              $("#modal-default-status-waiting").modal('show');

              $("#time_posted").html(dataPosted);
              $("#title_ann_waiting").val(dataTitle);
              image.setAttribute("src", `../img/${dataImage}`)
              $("#main_ann_waiting").val(dataMain);

            })

            $(".ksg").click(function(e){
              e.preventDefault();

              klikAll = 1;
              console.log(klikAll);
              let tempatBanner = document.querySelector("#banner_see")
              let getNama      = $(this).data('from')
              let dataId       = $(this).data('id')
              let dataNama     = $(this).data('from')
              let dataPosted   = $(this).data('time_posted')
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
                $("#via_ann_see").val(dataPosted)
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

            $(".detail_status").click(function(e) {
              e.preventDefault();

              let dataTitle         = $(this).data("title");
              let tempatBanner      = document.querySelector("#banner_see_status")
              let dataBanner        = $(this).data('banner')
              let dataMain          = $(this).data('main')
              let dataDateApproved  = $(this).data('time_approved')

              $("#modal-default-see-status").modal('show');
              $("#modal-default-all-status-approved").modal("hide");

              title_see_status.setAttribute("readonly", "")
              title_see_status.style.backgroundColor = '#eee'

              main_see_status.setAttribute("readonly", "")
              main_see_status.style.backgroundColor = '#eee'

              title_see_status.value = dataTitle
              tempatBanner.setAttribute("src", `../img/${dataBanner}`)
              main_see_status.value  = dataMain
              $("#time_approved_status").html(dataDateApproved)

            })

            $(".detail_status_not_approve").click(function(e) {

              e.preventDefault();
              let dataTitle            = $(this).data("title");
              let tempatBanner         = document.querySelector("#banner_see_status_not_approved")
              let dataBanner           = $(this).data('banner')
              let dataMain             = $(this).data('main')
              let dataDateNotApproved  = $(this).data('time_not_approved')
              let dataReason           = $(this).data('reason')

              $("#modal-default-see-status-not-approved").modal('show');
              $("#modal-default-all-status-not-approved").modal("hide");

              title_see_status_not_approved.setAttribute("readonly", "")
              title_see_status_not_approved.style.backgroundColor = '#eee'

              $("#reason_see_not_approved").val(dataReason)
              main_see_status_not_approved.setAttribute("readonly", "")
              main_see_status_not_approved.style.backgroundColor = '#eee'

              title_see_status_not_approved.value = dataTitle
              tempatBanner.setAttribute("src", `../img/${dataBanner}`)
              main_see_status_not_approved.value  = dataMain
              $("#time_not_approved_status").html(dataDateNotApproved)

            })

            $(".detail_status_waiting").click(function(e) {

              e.preventDefault();

              let dataTitle         = $(this).data("title");
              let tempatBanner      = document.querySelector("#banner_see_status_waiting")
              let dataBanner        = $(this).data('banner')
              let dataMain          = $(this).data('main')
              let dataDatePosted    = $(this).data('time_posted')

              $("#modal-default-see-status-waiting").modal('show');
              $("#modal-default-all-waiting").modal("hide");

              title_see_status_waiting.setAttribute("readonly", "")
              title_see_status_waiting.style.backgroundColor = '#eee'

              main_see_status_waiting.setAttribute("readonly", "")
              main_see_status_waiting.style.backgroundColor = '#eee'

              title_see_status_waiting.value = dataTitle
              tempatBanner.setAttribute("src", `../img/${dataBanner}`)
              main_see_status_waiting.value  = dataMain
              $("#time_status_waiting").html(dataDatePosted)

            })

          }
        };

        xhttp.open("GET", "data.php", true);
        xhttp.send();
      }, 1000)

    }

    $("#tutup_see").click(function(e){
      e.preventDefault();
      $("#modal-default-all").modal("show");
      $(".reason_see").hide();
      $("#approve_see").hide();
      $("#cancel_not_approve_see").hide();
      $("#save_reason_see").hide();
      $("#not_approve_see").show();
      $("#cancel_not_approve_see").hide();
      $("#approve_see").show();
    })

    $("#tutup_see_status").click(function(e){
      e.preventDefault();
      $("#modal-default-all-status-approved").modal("show");
    })

    $("#tutup_see_status_not_approved").click(function(e){
      e.preventDefault();
      $("#modal-default-all-status-not-approved").modal("show");
    })

    $("#tutup_see_status_waiting").click(function(e){
      e.preventDefault();
      $("#modal-default-all-waiting").modal("show");
    })

    $("#close_approve_see").click(function(e){
      e.preventDefault();
      $("#modal-default-all").modal("show");
      $(".reason_see").hide();
      $("#approve_see").hide();
      $("#cancel_not_approve_see").hide();
      $("#save_reason_see").hide();
      $("#not_approve_see").show();
      $("#cancel_not_approve_see").hide();
      $("#approve_see").show();
    })

    $("#close_approve_see_status").click(function(e) {
      e.preventDefault();
      $("#modal-default-all-status-approved").modal("show");
    })

    $("#close_see_status_not_approved").click(function(e) {
      e.preventDefault();
      $("#modal-default-all-status-not-approved").modal("show");
    })

    $("#close_see_status_waiting").click(function(e) {
      e.preventDefault();
      $("#modal-default-all-waiting").modal("show");
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
