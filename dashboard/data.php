<?php  

	require_once "../dbconfig.php";

	if (!$user->isLoggedIn()) {  
        header("location:/mading"); //Redirect ke halaman login  
    }  

	$countDataMessage = $user->countDataMessage();

	$status_approve = "";

	function getDateTimeDiff($tanggal) {
    
        date_default_timezone_set("Asia/Jakarta");
        $now_timestamp = strtotime(date('Y-m-d H:i:s'));
        $diff_timestamp = $now_timestamp - strtotime($tanggal);

        if ($diff_timestamp < 60) {
          return 'Beberapa detik yang lalu';
        } else if ( $diff_timestamp >= 60 && $diff_timestamp < 3600 ) {
          return round($diff_timestamp/60) . ' Menit yang lalu';
        } else if ( $diff_timestamp >= 3600 && $diff_timestamp < 86400 ) {
          return round($diff_timestamp/3600) . ' Jam yang lalu';
        } else if ( $diff_timestamp >= 86400 && $diff_timestamp < (86400*30) ) {
          return round($diff_timestamp/(86400)). ' Hari yang lalu';
        } else if ( $diff_timestamp >= (86400*30) && $diff_timestamp < (86400*365) ) {
          return round($diff_timestamp/(86400*30)) . ' Bulan yang lalu';
        } else {
          return round($diff_timestamp/(86400*365)) . ' Tahun yang lalu';
        }

    }

	if (isset($_POST['message_title']) && isset($_POST['message_info']) && isset($_POST['status_approve']) && isset($_POST['id']) && isset($_POST['reason']) ) {
		
		$messsage_title = $_POST['message_title'];
		$message_info 	= $_POST['message_info'];
		$status_approve = $_POST['status_approve'];
		$id 			= $_POST['id'];
		$user_id 		= $_SESSION['user_id'];
		$reason 		= $_POST['reason'];
		
		$arr = [];

		if ($reason == 0) {
			
			$getDataNoApprove = $user->getDataNoApprove();

			$countDataNotYetAprrove = $user->countDataMessage();

	        $arr['id']              = $id;
	        $arr['user_id']         = $user_id;
	        $arr['message_title']   = $messsage_title;
	        $arr['message_info']    = $message_info;
	        $arr['status_approve']  = $status_approve;
	        $arr['not_yet_approve'] = $countDataNotYetAprrove;
			
			$user->updateDataApprove($messsage_title, $message_info, $status_approve, $id);

		} else if ( $reason === '' ){

			$user->updateDataApprove($messsage_title, $message_info, $status_approve, $id);
			$reason = "tidak ada komentar";
			$user->insertDataReason($id, $reason);

			$countDataNotYetAprrove = $user->countDataMessage();

	        $arr['id']              = $id;
	        $arr['user_id']         = $user_id;
	        $arr['message_title']   = $messsage_title;
	        $arr['message_info']    = $message_info;
	        $arr['status_approve']  = $status_approve ;
	        $arr['not_yet_approve'] = $countDataNotYetAprrove;
	        $arr['reason']          = $reason;

		} else if ( $reason !== '' ) {
			

			$user->updateDataApprove($messsage_title, $message_info, $status_approve, $id);
			$user->insertDataReason($id, $reason);

			$countDataNotYetAprrove = $user->countDataMessage();

	        $arr['id']              = $id;
	        $arr['user_id']         = $user_id;
	        $arr['message_title']   = $messsage_title;
	        $arr['message_info']    = $message_info;
	        $arr['status_approve']  = $status_approve ;
	        $arr['not_yet_approve'] = $countDataNotYetAprrove;
	        $arr['reason']          = $reason;

		}
		
		echo json_encode($arr);

	} else if ( isset($_POST['countData']) && isset($_POST['role']) ) {

		$getDataMessage   = $user->getDataMessage(1);

		$arr = [];
		$outputNya = '';

		$server 	= "localhost";
		$username	= "root";
		$password 	= "";
		$database 	= "mading_practice";

		$conn = mysqli_connect($server, $username, $password, $database);

		$queryNya = "
			SELECT message_approve.id as message_id, message_approve.message_title as judul_pesan, message_approve.image as image, message_approve.message_info as isi_pesan, message_approve.status_approve as status_approve, message_approve.user_id as user_id, users.id as id_users, users.nama_user as nama_user, users.email as email FROM message_approve 
            LEFT JOIN users
            ON message_approve.user_id = users.id
            WHERE message_approve.status_approve = 1 ";
		
		$result = mysqli_query($conn, $queryNya);

		while ($row = mysqli_fetch_array($result)) { 
			$outputNya .= 
			'
				<li class="show_data" data-toggle="modal" data-id="'. $row['message_id'] .'" data-from="'. $row['nama_user'] .'" data-img="'. $row['image'] .'" data-target="modal-default">
	                  <a href="#">
	                    <div class="pull-left">
	                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
	                    </div>
	                    <h4>
	                      <p style="font-size:10px;"> From : '. $row['nama_user'] .' </p>
	                      <p style="font-size:10px;"> Via<span style="margin-left: 11px;"></span>: Admin </p>
	                    </h4>
	                    <h4> '. $row['isi_pesan'] .' </h4>
	                  </a>
	          	</li>
	        ';
	    }

	    $arr['jumlah_notif'] = $_POST['countData'];
		$arr['display_html'] = $outputNya;

		echo json_encode($arr);

	} else if (isset($_POST['message_title']) && isset($_POST['message_info']) && isset($_POST['status_approve']) && isset($_POST['id'])) {

		$message_title  = $_POST['message_title'];
		$message_info 	= $_POST['message_info'];
		$status_approve = $_POST['status_approve'];
		$id 		    = $_POST['id'];

		$user->updateDataApprove($message_title, $message_info, $status_approve, $id);
		// $user->updateData($messsage_title, $message_info, $status_approve, $id);
		$getDataNoApprove = $user->getDataNoApprove();

		$arr = [];

		$countDataNotYetAprrove = $user->countDataMessage();

        $arr['id']              = $id;
        $arr['message_title']   = $message_title;
        $arr['message_info']    = $message_info;
        $arr['status_approve']  = $status_approve ;
        $arr['not_yet_approve'] = $countDataNotYetAprrove;

		echo json_encode($arr);

	} else if (isset($_POST['title']) && isset($_POST['image']) && isset($_POST['announcement']) && isset($_POST['status_approve']) && isset($_POST['user_id']) && isset($_POST['form'])) {

		$message_title 	= $_POST['title'];
		$message_info 	= $_POST['announcement'];
		$image 			= $_POST['image'];
		$status_approve = $_POST['status_approve'];
		$user_id        = $_POST['user_id'];
		$namaFile 		= $_POST['form'];


		$arr = [];

        $arr['message_title']   = $message_title;
        $arr['message_info']    = $message_info;
        $arr['status_approve']  = $status_approve;
        $arr['user_id']         = $user_id;
        $arr['nama_file']       = $namaFile;

		echo json_encode($arr);exit;
		$user->insertDataMessageApprove($message_title, $message_info, $image, $status_approve, $user_id);

	} else {

		$userId = $_SESSION['user_id'];
		// echo $userId;exit;

		// Role Lain
		$getShortDataNotifMessage  				= $user->getShortDataNotifMessage(2,3, $userId);
	 	$getAllDataApproveAndNotApproveMessage  = $user->getAllDataApproveAndNoApproveMessage(2,3);

		// Role HRD
		$getShortDataNotifMessageHRD  			= $user->getShortDataNotifMessageHRD(1);
	 	$getAllDataNotYetApproveMessageHRD 		= $user->getAllDataNotYetApproveMessage(1);

	 	$getAllDataApproveMessage               = $user->getAllDataApproveMessage();
	 	$getAllDataApproveMessageById      		= $user->getAllDataApproveMessageById(2);
	 	$countDataNotYetAprrove 				= $user->countDataMessage(2);
	 	$countDataNotYetAprroveHRD 				= $user->countDataMessage();
	 	// var_dump(count($getShortDataNotifMessageHRD));exit;

		$arr = [];
		$outputNyaHRD    = '';
		$outputOtherRole = '';
		$output_all      = '';
		$output_all_hrd  = '';
		$allDataApprove  = '';
		$forImage        = '';

		// Role HRD
		if (count($getShortDataNotifMessageHRD) != 0) {

		    for ($i=0; $i < count($getShortDataNotifMessageHRD); $i++) {
		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getShortDataNotifMessageHRD[$i]['isi_pesan']) > 27 ? $isiPesan .= "<h4>" . substr($getShortDataNotifMessageHRD[$i]['isi_pesan'], 0, 27) . " ...." . "</h4>" : "<h4>". $getShortDataNotifMessageHRD[$i]['isi_pesan'] . "</h4>";
		    	$outputNyaHRD .= 
				'
					<li class="show_data" data-toggle="modal" data-id="'. $getShortDataNotifMessageHRD[$i]['message_id'] .'" data-from="'. $getShortDataNotifMessageHRD[$i]['nama_user'] .'" data-title="'. $getShortDataNotifMessageHRD[$i]['judul_pesan'] .'" data-img="'. $getShortDataNotifMessageHRD[$i]['image'] .'" data-main="'. $getShortDataNotifMessageHRD[$i]['isi_pesan'] .'" data-target="modal-default">
		                  <a href="#">
		                    <div class="pull-left">
		                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		                    </div>
		                    <h4>
		                      <p style="font-size:10px;"> From : '. $getShortDataNotifMessageHRD[$i]['nama_user'] .' </p>
		                      <p style="font-size:10px;"> Via<span style="margin-left: 11px;"></span>: Admin </p>
		                      <small><i class="fa fa-clock-o"></i> '. getDateTimeDiff($getShortDataNotifMessageHRD[$i]['tanggal_buat_announcement'] ).' </small>
		                    </h4>
		                    ' . $semuaPesan . '
		                  </a>
		          	</li>
		        ';
		        
		    }

		    for ($i=0; $i < count($getAllDataNotYetApproveMessageHRD); $i++) {

		    	$output_all_hrd .= 
				'
					<div style="border: 3px solid black;">
						<div class="box-header" style="background-color: brown;">
							<div data-toggle="modal" data-id="'. $getAllDataNotYetApproveMessageHRD[$i]['message_id'] .'" data-from="'. $getAllDataNotYetApproveMessageHRD[$i]['nama_user'] .'" data-title="'. $getAllDataNotYetApproveMessageHRD[$i]['judul_pesan'] .'" data-main="'. $getAllDataNotYetApproveMessageHRD[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; height: 15px;">
				                <div class="box-header" style="position: absolute; left: auto; margin-top: -13px; color: white;">
				                  <h3 class="box-title">From : '. $getAllDataNotYetApproveMessageHRD[$i]['nama_user'] .'</h3>               
				                </div>
				                <div class="box-header" style="position: absolute; right: 0; margin-top: -13px; color: white;">
				                  <h3 class="box-title">Via : Admin</h3>               
				                </div>
			              	</div>
		              	</div>
		              	<br>
		              	<div class="box-body" style="background-color: #ddd; margin-top: -20px;">
			              	<div class="box-body ksg" data-toggle="modal" data-gambar="'. $getAllDataNotYetApproveMessageHRD[$i]['gambar'] .'" data-id="'. $getAllDataNotYetApproveMessageHRD[$i]['message_id'] .'" data-from="'. $getAllDataNotYetApproveMessageHRD[$i]['nama_user'] .'" data-title="'. $getAllDataNotYetApproveMessageHRD[$i]['judul_pesan'] .'" data-main="'. $getAllDataNotYetApproveMessageHRD[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; margin-bottom: 15px;">
				                <div class="pull-lefts" style="margin-bottom: 10px; ">
				                  <img src="../dist/img/user2-160x160.jpg" class="img-circle" style="width: 10%;" alt="User Image">
				                </div>
				                <p style="font-size:13px;"> Title <span style="margin-left: 60px;"> : </span> <strong> '. $getAllDataNotYetApproveMessageHRD[$i]['judul_pesan'] .' </strong> </p>
				                <p style="font-size:13px;"> Announcement : '. $getAllDataNotYetApproveMessageHRD[$i]['isi_pesan'] .' </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
		        ';
		    }

		} else {

			$outputNyaHRD .= '
				<li>
                  	<a href="#">
	                    <h4> '. "No unapproved data" .' </h4>
                  	</a>
	          	</li>
	        ';

	        $output_all_hrd .= '<div class="ksg" style="position: relative; width: 100%; margin-bottom: 35px;">KOSONG</div>';

		}

		if (count($getAllDataApproveMessage) != 0) {
			
			for ($i=0; $i < count($getAllDataApproveMessage); $i++) { 
				$allDataApprove .= 
				'
				<div class="col-md-4" style="height: 500px;">
					<div class="box box-widget" style="height: auto;">
			            <div class="box-header with-border">

			              <div class="user-block">
			                <img class="img-circle" src="../dist/img/defaults.jpg" alt="User Image">
			                <span class="username"><a href="#">' . $getAllDataApproveMessage[$i]['nama_user'] . '</a></span>
			                <span class="description">Shared publicly - ' . date('d F Y H:i:s', strtotime($getAllDataApproveMessage[$i]['tgl_approve'])) . ' </span>
			              </div>

			              <div class="box-tools">
			                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
			                  <i class="fa fa-bookmark-o"></i></button>
			              </div>

			            </div>

			            <div class="box-body portfolio" data-from=' . $getAllDataApproveMessage[$i]['nama_user'] . ' data-banner='. $getAllDataApproveMessage[$i]['banner'] .'>
			              <h5> <strong> Title  : ' . $getAllDataApproveMessage[$i]['judul_pesan'] . ' </strong> </h5>
			              <img class="img-responsive pad bnnr" style="width: 300px; height: 300px;" src="../img/'.$getAllDataApproveMessage[$i]['banner'].'" alt="Photo">

			              <p> <strong> ' . $getAllDataApproveMessage[$i]['isi_pesan'] . ' </strong> </p>
			            </div>

		          	</div>
		        </div>
		        ';
			}

		} else {

			$allDataApprove .= "<h1> Kosong </h1>";

		}
		// Akhir Role HRD

		// Role Lain 
		if (count($getShortDataNotifMessage) != 0) {

			for ($i=0; $i < count($getShortDataNotifMessage); $i++) {
		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getShortDataNotifMessage[$i]['isi_pesan']) > 27 ? $isiPesan .= "<h4>" . substr($getShortDataNotifMessage[$i]['isi_pesan'], 0, 27) . " ...." . "</h4>" : "<h4>". $getShortDataNotifMessage[$i]['isi_pesan'] . "</h4>";
		    	$outputOtherRole .= 
				'
					<li class="show_data_status" data-toggle="modal" data-id="'. $getShortDataNotifMessage[$i]['message_id'] .'" data-from="'. $getShortDataNotifMessage[$i]['nama_user'] .'" data-title="'. $getShortDataNotifMessage[$i]['judul_pesan'] .'" data-time_approved="'. date('H:i:s', strtotime($getShortDataNotifMessage[$i]['tanggal_approve']) ) .'" data-img="'. $getShortDataNotifMessage[$i]['image'] .'" data-main="'. $getShortDataNotifMessage[$i]['isi_pesan'] .'" data-target="modal-default-status">
		                  <a href="#">
		                    <div class="pull-left">
		                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		                    </div>
		                    <h4>
		                      <p style="font-size:10px;"> From : '. $getShortDataNotifMessage[$i]['nama_user'] .' </p>
		                      <p style="font-size:10px;"> Via<span style="margin-left: 11px;"></span>: Admin </p>
		                      <small><i class="fa fa-clock-o"></i> '. getDateTimeDiff($getShortDataNotifMessage[$i]['tanggal_approve'] ).' </small>
		                    </h4>
		                    ' . $semuaPesan . '
		                  </a>
		          	</li>
		        ';
		        
		    }

		    for ($i=0; $i < count($getAllDataApproveAndNotApproveMessage); $i++) {

		    	$output_all .= 
				'
					<div style="border: 3px solid black;">
						<div class="box-header" style="background-color: brown;">
							<div data-toggle="modal" data-id="'. $getAllDataApproveAndNotApproveMessage[$i]['message_id'] .'" data-from="'. $getAllDataApproveAndNotApproveMessage[$i]['nama_user'] .'" data-title="'. $getAllDataApproveAndNotApproveMessage[$i]['judul_pesan'] .'" data-main="'. $getAllDataApproveAndNotApproveMessage[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; height: 15px;">
				                <div class="box-header" style="position: absolute; left: auto; margin-top: -13px; color: white;">
				                  <h3 class="box-title">From : '. $getAllDataApproveAndNotApproveMessage[$i]['nama_user'] .'</h3>               
				                </div>
				                <div class="box-header" style="position: absolute; right: 0; margin-top: -13px; color: white;">
				                  <h3 class="box-title">Via : Admin</h3>               
				                </div>
			              	</div>
		              	</div>
		              	<br>
		              	<div class="box-body" style="background-color: #ddd; margin-top: -20px;">
			              	<div class="box-body ksg" data-toggle="modal" data-id="'. $getAllDataApproveAndNotApproveMessage[$i]['message_id'] .'" data-from="'. $getAllDataApproveAndNotApproveMessage[$i]['nama_user'] .'" data-title="'. $getAllDataApproveAndNotApproveMessage[$i]['judul_pesan'] .'" data-main="'. $getAllDataApproveAndNotApproveMessage[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; margin-bottom: 15px;">
				                <div class="pull-lefts" style="margin-bottom: 10px; ">
				                  <img src="../dist/img/user2-160x160.jpg" class="img-circle" style="width: 10%;" alt="User Image">
				                </div>
				                <p style="font-size:13px;"> Title <span style="margin-left: 60px;"> : </span> <strong> '. $getAllDataApproveAndNotApproveMessage[$i]['judul_pesan'] .' </strong> </p>
				                <p style="font-size:13px;"> Announcement : '. $getAllDataApproveAndNotApproveMessage[$i]['isi_pesan'] .' </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
		        ';
		    }

		}

		// Akhir Role Lain

		$forImage .= '
			<div>
               <label for="exampleInputEmail1"> Upload Image Mading (*Tidak lebih dari 2 MB) </label>
               <input type="file" class="form-control fileGambar" name="banner" id="buat_banner">
               <img src="default.jpg" id="gambar">
            </div>';

	    $arr['jumlah_notif_hrd'] 		= $countDataNotYetAprroveHRD;
		$arr['display_html_hrd'] 		= $outputNyaHRD;
		$arr['display_all_html_hrd'] 	= $output_all_hrd;

	    $arr['jumlah_notif'] 			= $countDataNotYetAprrove;
		$arr['display_html']	 		= $outputOtherRole;
		$arr['count_message']			= count($getAllDataNotYetApproveMessageHRD);
		$arr['display_html_approve']    = $allDataApprove;
		$arr['jumlah_approve_by_id'] 	= count($getAllDataApproveMessageById);
		$arr['upload_img'] 				= $forImage;
		$arr['all_data'] 				= count($getAllDataApproveMessage);

		echo json_encode($arr);

	}

	exit;

?>