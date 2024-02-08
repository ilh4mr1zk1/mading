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
		$getShortDataNotifMessageApprove  		= $user->getShortDataNotifMessageApprove(2, $userId);
		$getShortDataNotifMessageNotApprove  	= $user->getShortDataNotifMessageNotApprove(3, $userId);
		$getShortDataWaitingMessage 		 	= $user->getShortDataWaitingMessage(1, $userId);
		$getDataWaitingConfirm					= $user->waitingDataResponse(1, $userId);
	 	$getAllDataApproveAndNotApproveMessage  = $user->getAllDataApproveAndNoApproveMessage(2,3);
	 	$getAllDataApproveAndNotApproveMessage  = $user->getAllDataApproveAndNoApproveMessage(2,3);

		// Role HRD
		$getShortDataNotifMessageHRD  			= $user->getShortDataNotifMessageHRD(1);
	 	$getAllDataNotYetApproveMessageHRD 		= $user->getAllDataNotYetApproveMessage(1);

	 	$getAllDataApproveMessage               = $user->getAllDataApproveMessage();
	 	$getAllDataApproveMessageById      		= $user->getAllDataStatusApproveMessageById(2);
	 	$getAllDataNotApproveMessageById      	= $user->getAllDataStatusNotApproveMessageById(3);
	 	$countDataNotYetAprrove 				= $user->countDataMessage(2);
	 	$countDataNotYetAprroveHRD 				= $user->countDataMessage();

		$arr 									= [];
		$outputNyaHRD    	 					= '';
		$outputMessagesApproveOtherRole 	 	= '';
		$outputMessagesNotApproveOtherRole		= '';
		$output_all      	 					= '';
		$output_all_hrd  	 					= '';
		$output_all_approve  					= '';
		$output_all_not_approve  				= '';
		$allDataApprove  	 					= '';
		$allDataWaiting  	 					= '';
		$forImage        	 					= '';
		$outputWaitingResponse 					= '';

		// Role HRD
		if (count($getShortDataNotifMessageHRD) != 0) {

		    for ($i=0; $i < count($getShortDataNotifMessageHRD); $i++) {
		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getShortDataNotifMessageHRD[$i]['judul_pesan']) > 27 ? $isiPesan .= substr($getShortDataNotifMessageHRD[$i]['judul_pesan'], 0, 27) . " ...." : $getShortDataNotifMessageHRD[$i]['judul_pesan'];
		    	$outputNyaHRD .= 
				'
					<li class="show_data" data-toggle="modal" data-id="'. $getShortDataNotifMessageHRD[$i]['message_id'] .'" data-from="'. $getShortDataNotifMessageHRD[$i]['nama_user'] .'" data-time_posted="'. date('d M Y H:i:s', strtotime($getShortDataNotifMessageHRD[$i]['tanggal_buat_announcement'])) .'" data-title="'. $getShortDataNotifMessageHRD[$i]['judul_pesan'] .'" data-img="'. $getShortDataNotifMessageHRD[$i]['image'] .'" data-main="'. $getShortDataNotifMessageHRD[$i]['isi_pesan'] .'" data-target="modal-default">
		                  <a href="#">
		                    <div class="pull-left">
		                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		                    </div>
		                    <h4 style="font-size: 12px;">
		                      <p style="font-size:10px;"> From : '. $getShortDataNotifMessageHRD[$i]['nama_user'] .' </p>
		                      <small><i class="fa fa-clock-o"></i> '. getDateTimeDiff($getShortDataNotifMessageHRD[$i]['tanggal_buat_announcement'] ).' </small>
		                    ' . "Title : <strong> " . $semuaPesan . '</strong>' . '
		                    </h4>
		                  </a>
		          	</li>
		        ';
		        
		    }

		    for ($i=0; $i < count($getAllDataNotYetApproveMessageHRD); $i++) {

		    	$output_all_hrd .= 
				'
					<div style="border: 3px solid black;">
						<div class="box-header" style="background-color: brown;">
							<div data-toggle="modal" data-id="'. $getAllDataNotYetApproveMessageHRD[$i]['message_id'] .'" data-from="'. $getAllDataNotYetApproveMessageHRD[$i]['nama_user'] .'" data-time_posted="'. date('d M Y H:i:s', strtotime($getAllDataNotYetApproveMessageHRD[$i]['tanggal_buat_announcement'])) .'" data-title="'. $getAllDataNotYetApproveMessageHRD[$i]['judul_pesan'] .'" data-main="'. $getAllDataNotYetApproveMessageHRD[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; height: 15px;">
				                <div class="box-header" style="position: absolute; left: auto; margin-top: -13px; color: white;">
				                  <h3 class="box-title">From : '. $getAllDataNotYetApproveMessageHRD[$i]['nama_user'] .'</h3>               
				                </div>
				                <div class="box-header" style="position: absolute; right: 0; margin-top: -13px; color: white;">
				                  <h3 class="box-title">Posted : '. getDateTimeDiff($getAllDataNotYetApproveMessageHRD[$i]['tanggal_buat_announcement'] ).'</h3>               
				                </div>
			              	</div>
		              	</div>
		              	<br>
		              	<div class="box-body" style="background-color: #ddd; margin-top: -20px;">
			              	<div class="box-body ksg" data-toggle="modal" data-gambar="'. $getAllDataNotYetApproveMessageHRD[$i]['gambar'] .'" data-id="'. $getAllDataNotYetApproveMessageHRD[$i]['message_id'] .'" data-time_posted="'. date('d M Y H:i:s', strtotime($getAllDataNotYetApproveMessageHRD[$i]['tanggal_buat_announcement'])) .'" data-from="'. $getAllDataNotYetApproveMessageHRD[$i]['nama_user'] .'" data-title="'. $getAllDataNotYetApproveMessageHRD[$i]['judul_pesan'] .'" data-main="'. $getAllDataNotYetApproveMessageHRD[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; margin-bottom: 15px;">
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
		if (count($getShortDataNotifMessageApprove) != 0) {

			for ($i=0; $i < count($getShortDataNotifMessageApprove); $i++) {
		    	
		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getShortDataNotifMessageApprove[$i]['isi_pesan']) > 27 ? $isiPesan .= "<h4>" . substr($getShortDataNotifMessageApprove[$i]['isi_pesan'], 0, 27) . " ...." . "</h4>" : "<h4>". $getShortDataNotifMessageApprove[$i]['isi_pesan'] . "</h4>";

		    	$judulPesan = '';
		    	$titleAnn = strlen($getShortDataNotifMessageApprove[$i]['judul_pesan']) > 26 ? $judulPesan .= substr($getShortDataNotifMessageApprove[$i]['judul_pesan'], 0, 26) . " ...." : $getShortDataNotifMessageApprove[$i]['judul_pesan'];

		    	$outputMessagesApproveOtherRole .= 
				'
					<li class="show_data_status_approve" data-toggle="modal" data-id="'. $getShortDataNotifMessageApprove[$i]['message_id'] .'" data-from="'. $getShortDataNotifMessageApprove[$i]['nama_user'] .'" data-title="'. $getShortDataNotifMessageApprove[$i]['judul_pesan'] .'" data-time_approved="'. date('d M Y  H:i:s', strtotime($getShortDataNotifMessageApprove[$i]['tanggal_konfirmasi']) ) .'" data-img="'. $getShortDataNotifMessageApprove[$i]['image'] .'" data-main="'. $getShortDataNotifMessageApprove[$i]['isi_pesan'] .'" data-target="modal-default-status">
		                  <a href="#">
		                    <div class="pull-left">
		                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		                    </div>
		                    <h4 style="font-size: 12px;">
		                      <p style="font-size:10px;"> From : '. "HRD" .' </p>
		                      <small><i class="fa fa-clock-o"></i> '. getDateTimeDiff($getShortDataNotifMessageApprove[$i]['tanggal_konfirmasi'] ).' </small>
		                    ' . 'Title : <strong> ' . $titleAnn . '</strong>' . '
		                    </h4>
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

		    for ($i=0; $i < count($getAllDataApproveMessageById); $i++) { 

		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getAllDataApproveMessageById[$i]['isi_pesan']) > 35 ? $isiPesan .= substr($getAllDataApproveMessageById[$i]['isi_pesan'], 0, 35) . " ...." : $getAllDataApproveMessageById[$i]['isi_pesan'];

		    	$output_all_approve .=
		    	'
		    		<div>
		              	<div class="box-body" style="background-color: #ddd; margin-top: -20px; border: 3px solid black;">
			              	<div class="box-body detail_status" data-toggle="modal" data-time_approved="'. date('d M Y H:i:s', strtotime($getAllDataApproveMessageById[$i]['tgl_approve'])) .'" data-id="'. $getAllDataApproveMessageById[$i]['message_id'] .'" data-from="'. $getAllDataApproveMessageById[$i]['nama_user'] .'" data-title="'. $getAllDataApproveMessageById[$i]['judul_pesan'] .'" data-main="'. $getAllDataApproveMessageById[$i]['isi_pesan'] .'" data-banner="'. $getAllDataApproveMessageById[$i]['banner'] .'" data-target="modal-default" style="position: relative; width: 100%; margin-bottom: 15px;">
				                
				                <p style="font-size:13px;"> Title <span style="margin-left: 60px;"> : </span> <strong> '. $getAllDataApproveMessageById[$i]['judul_pesan'] .' </strong> </p>
				                <p style="font-size:13px;"> Announcement : '. $semuaPesan .' </p>
				                <p style="font-size:13px;"> Date Approved : '. date('d M Y H:i:s', strtotime($getAllDataApproveMessageById[$i]['tgl_approve'])) .' </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
		            <br>
		    	';

		    }

		}

		if (count($getShortDataNotifMessageNotApprove) != 0) {

			for ($i=0; $i < count($getShortDataNotifMessageNotApprove); $i++) {
		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getShortDataNotifMessageNotApprove[$i]['judul_pesan']) > 27 ? $isiPesan .= substr($getShortDataNotifMessageNotApprove[$i]['judul_pesan'], 0, 27) . " ...." : $getShortDataNotifMessageNotApprove[$i]['judul_pesan'];
		    	$outputMessagesNotApproveOtherRole .= 
				'
					<li class="show_data_status_not_approve" data-toggle="modal" data-id="'. $getShortDataNotifMessageNotApprove[$i]['message_id'] .'" data-from="'. $getShortDataNotifMessageNotApprove[$i]['nama_user'] .'" data-reason="'. $getShortDataNotifMessageNotApprove[$i]['alasan_tidak_disetujui'] .'" data-title="'. $getShortDataNotifMessageNotApprove[$i]['judul_pesan'] .'" data-time_not_approved="'. date('d M Y  H:i:s', strtotime($getShortDataNotifMessageNotApprove[$i]['tanggal_konfirmasi']) ) .'" data-img="'. $getShortDataNotifMessageNotApprove[$i]['image'] .'" data-main="'. $getShortDataNotifMessageNotApprove[$i]['isi_pesan'] .'" data-target="modal-default-status">
		                  <a href="#">
		                    <div class="pull-left">
		                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		                    </div>
		                    <h4 style="font-size: 12px;">
		                      <p style="font-size:10px;"> From : '. $getShortDataNotifMessageNotApprove[$i]['nama_user'] .' </p>
		                      <p style="font-size:10px;"> Via<span style="margin-left: 11px;"></span>: Admin </p>
		                      <small><i class="fa fa-clock-o"></i> '. getDateTimeDiff($getShortDataNotifMessageNotApprove[$i]['tanggal_konfirmasi'] ).' </small>
		                    ' . "Title : <strong> " . $semuaPesan . "</strong>" . '
		                    </h4>
		                  </a>
		          	</li>
		        ';
		        
		    }

		    for ($i=0; $i < count($getAllDataNotApproveMessageById); $i++) { 

		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getAllDataNotApproveMessageById[$i]['isi_pesan']) > 35 ? $isiPesan .= substr($getAllDataNotApproveMessageById[$i]['isi_pesan'], 0, 35) . " ...." : $getAllDataNotApproveMessageById[$i]['isi_pesan'];

		    	$output_all_not_approve .=
		    	'
		    		<div>
		              	<div class="box-body" style="background-color: #ddd; margin-top: -20px; border: 3px solid black;">
			              	<div class="box-body detail_status_not_approve" data-toggle="modal" data-time_not_approved="'. date('d M Y H:i:s', strtotime($getAllDataNotApproveMessageById[$i]['tgl_approve'])) .'" data-id="'. $getAllDataNotApproveMessageById[$i]['message_id'] .'" data-from="'. $getAllDataNotApproveMessageById[$i]['nama_user'] .'" data-reason="'. $getAllDataNotApproveMessageById[$i]['alasan_tidak_disetujui'] .'" data-title="'. $getAllDataNotApproveMessageById[$i]['judul_pesan'] .'" data-main="'. $getAllDataNotApproveMessageById[$i]['isi_pesan'] .'" data-banner="'. $getAllDataNotApproveMessageById[$i]['banner'] .'" data-target="modal-default" style="position: relative; width: 100%; margin-bottom: 15px;">
				                
				                <p style="font-size:13px;"> Title <span style="margin-left: 60px;"> : </span> <strong> '. $getAllDataNotApproveMessageById[$i]['judul_pesan'] .' </strong> </p>
				                <p style="font-size:13px;"> Announcement : '. $semuaPesan .' </p>
				                <p style="font-size:13px;"> Date Not Approved : '. date('d M Y H:i:s', strtotime($getAllDataNotApproveMessageById[$i]['tgl_approve'])) .' </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
		            <br>
		    	';

		    }

		}

		if (count($getShortDataWaitingMessage) != 0) {

			for ($i=0; $i < count($getShortDataWaitingMessage); $i++) {

				$isiPesan = ''; 
		    	$semuaPesan = strlen($getShortDataWaitingMessage[$i]['isi_pesan']) > 27 ? $isiPesan .= "<h4>" . substr($getShortDataWaitingMessage[$i]['isi_pesan'], 0, 27) . " ...." . "</h4>" : "<h4>". $getShortDataWaitingMessage[$i]['isi_pesan'] . "</h4>";
		    	
		    	$judulPesan = '';
		    	$titleAnn = strlen($getShortDataWaitingMessage[$i]['judul_pesan']) > 26 ? $judulPesan .= substr($getShortDataWaitingMessage[$i]['judul_pesan'], 0, 26) . " ...." : $getShortDataWaitingMessage[$i]['judul_pesan'];

				$outputWaitingResponse .= 
				'
					<li class="show_data_status_waiting" data-toggle="modal" data-id="'. $getShortDataWaitingMessage[$i]['message_id'] .'" data-title="'. $getShortDataWaitingMessage[$i]['judul_pesan'] .'" data-tgl_buat="'. $getShortDataWaitingMessage[$i]['tanggal_buat_announcement'] .'" data-img="'. $getShortDataWaitingMessage[$i]['image'] .'" data-main="'. $getShortDataWaitingMessage[$i]['isi_pesan'] .'" data-target="modal-default-waiting">
		                  <a href="#">
		                  	<div class="pull-left">
		                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		                    </div>
		                    <h4 style="font-size: 12px;">
		                      <p> ‎‎‎ </p>
		                      <br>
		                      <small><i class="fa fa-clock-o"></i> '. getDateTimeDiff($getShortDataWaitingMessage[$i]['tanggal_buat_announcement'] ).' </small>
		                    ' . 'Title : <strong> ' . $titleAnn . '</strong>' . '
		                    </h4>
		                  </a>
		          	</li>
				';

			}

		}

		if (count($getDataWaitingConfirm) != 0) {
			
			for ($i=0; $i < count($getDataWaitingConfirm); $i++) { 
				$isiPesan = ''; 
		    	$semuaPesan = strlen($getDataWaitingConfirm[$i]['isi_pesan']) > 27 ? $isiPesan .= substr($getDataWaitingConfirm[$i]['isi_pesan'], 0, 27) . " ...." : $getDataWaitingConfirm[$i]['isi_pesan'];
				$allDataWaiting .= 
				'
					<div>
		              	<div class="box-body" style="background-color: #ddd; margin-top: -20px; border: 3px solid black;">
			              	<div class="box-body detail_status_waiting" data-toggle="modal" data-time_posted="'. date('d M Y H:i:s', strtotime($getDataWaitingConfirm[$i]['tanggal_buat_announcement'])) .'" data-id="'. $getDataWaitingConfirm[$i]['message_id'] .'" data-title="'. $getDataWaitingConfirm[$i]['judul_pesan'] .'" data-main="'. $getDataWaitingConfirm[$i]['isi_pesan'] .'" data-banner="'. $getDataWaitingConfirm[$i]['banner'] .'" data-target="modal-default" style="position: relative; width: 100%; margin-bottom: 15px;">
				                
				                <p style="font-size:13px;"> Title <span style="margin-left: 65px;"> : </span> <strong style="margin-left: 5px;"> '. $getDataWaitingConfirm[$i]['judul_pesan'] .' </strong> </p>
				                <p style="font-size:13px;"> Announcement <span style="margin-left: 5px;"> : <span style="margin-left: 5px;"> '. $semuaPesan .' </span> </span> </p>
				                <p style="font-size:13px;"> Date Posted <span style="margin-left: 23.5px;"> : </span> <span style="margin-left: 5px;"> '. date('d M Y H:i:s', strtotime($getDataWaitingConfirm[$i]['tanggal_buat_announcement'])) .' <span> </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
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

	    $arr['jumlah_notif_hrd'] 				= $countDataNotYetAprroveHRD;
		$arr['display_html_hrd'] 				= $outputNyaHRD;
		$arr['display_all_html_hrd'] 			= $output_all_hrd;

	    $arr['jumlah_notif'] 					= $countDataNotYetAprrove;
		$arr['display_html_short_approve']		= $outputMessagesApproveOtherRole;
		$arr['display_html_short_not_approve']	= $outputMessagesNotApproveOtherRole;
		$arr['display_all_html_approve'] 		= $output_all_approve;
		$arr['display_all_html_not_approve']    = $output_all_not_approve;
		$arr['count_message']					= count($getAllDataNotYetApproveMessageHRD);
		$arr['display_html_approve']    		= $allDataApprove;
		$arr['display_html_waiting']    		= $allDataWaiting;
		$arr['jumlah_approve_by_id'] 			= count($getAllDataApproveMessageById);
		$arr['jumlah_not_approve_by_id'] 		= count($getAllDataNotApproveMessageById);
		$arr['upload_img'] 						= $forImage;
		$arr['all_data'] 						= count($getAllDataApproveMessage);
		$arr['count_waiting_response'] 			= count($getDataWaitingConfirm);
		$arr['display_html_short_waiting'] 		= $outputWaitingResponse;

		echo json_encode($arr);

	}

	exit;

?>