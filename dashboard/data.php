<?php  

	require_once "../dbconfig.php";

	if (!$user->isLoggedIn()) {  
        header("location:/mading"); //Redirect ke halaman login  
    }  

	$countDataMessage = $user->countDataMessage();

	$status_approve = "";

	if (isset($_POST['message_title']) && isset($_POST['message_info']) && isset($_POST['status_approve']) && isset($_POST['id']) && isset($_POST['reason']) ) {
		
		$messsage_title = $_POST['message_title'];
		$message_info 	= $_POST['message_info'];
		$status_approve = $_POST['status_approve'];
		$id 		    = $_POST['id'];
		$reason 		= $_POST['reason'];

		$user->updateData($messsage_title, $message_info, $status_approve, $id);

		if ($reason == 0) {
			
			$getDataNoApprove = $user->getDataNoApprove();

			$arr = [];

			$countDataNotYetAprrove = $user->countDataMessage();

	        $arr['id']              = $id;
	        $arr['message_title']   = $messsage_title;
	        $arr['message_info']    = $message_info;
	        $arr['status_approve']  = $status_approve;
	        $arr['not_yet_approve'] = $countDataNotYetAprrove;

			echo json_encode($arr);
			exit;

		} else if ( $reason === '' ){

			$reason = "tidak ada komentar";
			$user->insertDataReason($id, $reason);

		} else if ( $reason !== '' ) {
			
			$user->insertDataReason($id, $reason);

		}

		$getDataNoApprove = $user->getDataNoApprove();

		$arr = [];

		$countDataNotYetAprrove = $user->countDataMessage();

        $arr['id']              = $id;
        $arr['message_title']   = $messsage_title;
        $arr['message_info']    = $message_info;
        $arr['status_approve']  = $status_approve ;
        $arr['not_yet_approve'] = $countDataNotYetAprrove;
        $arr['reason']          = $reason;

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
			SELECT message.id as message_id, message.message_title as judul_pesan, message.message_info as isi_pesan, message.status_approve as status_approve, message.user_id as user_id, users.id as id_users, users.nama_user as nama_user, users.email as email FROM message 
            LEFT JOIN users
            ON message.user_id = users.id
            WHERE message.status_approve = 1 ";
		
		$result = mysqli_query($conn, $queryNya);

		while ($row = mysqli_fetch_array($result)) { 
			$outputNya .= 
			'
				<li class="show_data" data-toggle="modal" data-id="'. $row['message_id'] .'" data-from="'. $row['nama_user'] .'" data-target="modal-default">
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

	} else if (isset($_POST['title']) && isset($_POST['image']) && isset($_POST['announcement']) && isset($_POST['status_approve']) && isset($_POST['user_id'])) {

		$message_title 	= $_POST['title'];
		$message_info 	= $_POST['announcement'];
		$image 			= $_POST['image'];
		$status_approve = $_POST['status_approve'];
		$user_id        = $_POST['user_id'];

		$user->insertDataMessageApprove($message_title, $message_info, $image, $status_approve, $user_id);

		$arr = [];

        $arr['message_title']   = $message_title;
        $arr['message_info']    = $message_info;
        $arr['status_approve']  = $status_approve ;
        $arr['user_id']         = $user_id;

		echo json_encode($arr);

	} else {

		$getShortDataNotifMessage  		= $user->getShortDataNotifMessage(1);
	 	$getAllDataNotYetApproveMessage = $user->getAllDataNotYetApproveMessage(1);
	 	$getAllDataApproveMessage       = $user->getAllDataApproveMessage(2);
	 	$countDataNotYetAprrove 		= $user->countDataMessage();

		$arr = [];
		$outputNya       = '';
		$output_all      = '';
		$allDataApprove  = '';
		$forImage        = '';

		if (count($getShortDataNotifMessage) != 0) {

		    for ($i=0; $i < count($getShortDataNotifMessage); $i++) {
		    	$isiPesan = ''; 
		    	$semuaPesan = strlen($getShortDataNotifMessage[$i]['isi_pesan']) > 27 ? $isiPesan .= "<h4>" . substr($getShortDataNotifMessage[$i]['isi_pesan'], 0, 27) . " ...." . "</h4>" : "<h4>". $getShortDataNotifMessage[$i]['isi_pesan'] . "</h4>";
		    	$outputNya .= 
				'
					<li class="show_data" data-toggle="modal" data-id="'. $getShortDataNotifMessage[$i]['message_id'] .'" data-from="'. $getShortDataNotifMessage[$i]['nama_user'] .'" data-title="'. $getShortDataNotifMessage[$i]['judul_pesan'] .'" data-main="'. $getShortDataNotifMessage[$i]['isi_pesan'] .'" data-target="modal-default">
		                  <a href="#">
		                    <div class="pull-left">
		                      <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
		                    </div>
		                    <h4>
		                      <p style="font-size:10px;"> From : '. $getShortDataNotifMessage[$i]['nama_user'] .' </p>
		                      <p style="font-size:10px;"> Via<span style="margin-left: 11px;"></span>: Admin </p>
		                    </h4>
		                    ' . $semuaPesan . '
		                  </a>
		          	</li>
		        ';
		        
		    }

		    for ($i=0; $i < count($getAllDataNotYetApproveMessage); $i++) {

		    	$output_all .= 
				'
					<div style="border: 3px solid black;">
						<div class="box-header" style="background-color: brown;">
							<div data-toggle="modal" data-id="'. $getAllDataNotYetApproveMessage[$i]['message_id'] .'" data-from="'. $getAllDataNotYetApproveMessage[$i]['nama_user'] .'" data-title="'. $getAllDataNotYetApproveMessage[$i]['judul_pesan'] .'" data-main="'. $getAllDataNotYetApproveMessage[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; height: 15px;">
				                <div class="box-header" style="position: absolute; left: auto; margin-top: -13px; color: white;">
				                  <h3 class="box-title">From : '. $getAllDataNotYetApproveMessage[$i]['nama_user'] .'</h3>               
				                </div>
				                <div class="box-header" style="position: absolute; right: 0; margin-top: -13px; color: white;">
				                  <h3 class="box-title">Via : Admin</h3>               
				                </div>
			              	</div>
		              	</div>
		              	<br>
		              	<div class="box-body" style="background-color: #ddd; margin-top: -20px;">
			              	<div class="box-body ksg" data-toggle="modal" data-id="'. $getAllDataNotYetApproveMessage[$i]['message_id'] .'" data-from="'. $getAllDataNotYetApproveMessage[$i]['nama_user'] .'" data-title="'. $getAllDataNotYetApproveMessage[$i]['judul_pesan'] .'" data-main="'. $getAllDataNotYetApproveMessage[$i]['isi_pesan'] .'" data-target="modal-default" style="position: relative; width: 100%; margin-bottom: 15px;">
				                <div class="pull-lefts" style="margin-bottom: 10px; ">
				                  <img src="../dist/img/user2-160x160.jpg" class="img-circle" style="width: 10%;" alt="User Image">
				                </div>
				                <p style="font-size:13px;"> Title <span style="margin-left: 60px;"> : </span> <strong> '. $getAllDataNotYetApproveMessage[$i]['judul_pesan'] .' </strong> </p>
				                <p style="font-size:13px;"> Announcement : '. $getAllDataNotYetApproveMessage[$i]['isi_pesan'] .' </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
		        ';
		    }

		} else {

			$outputNya .= '
				<li>
                  	<a href="#">
	                    <h4> '. "No unapproved data" .' </h4>
                  	</a>
	          	</li>
	        ';

	        $output_all .= '<div class="ksg" style="position: relative; width: 100%; margin-bottom: 35px;">KOSONG</div>';

		}

		if (count($getAllDataApproveMessage) != 0) {
			
			for ($i=0; $i < count($getAllDataApproveMessage); $i++) { 
				$allDataApprove .= 
				'
				<div class="col-md-4">
					<div class="box box-widget">
			            <div class="box-header with-border">

			              <div class="user-block">
			                <img class="img-circle" src="../dist/img/defaults.jpg" alt="User Image">
			                <span class="username"><a href="#">' . $getAllDataApproveMessage[$i]['nama_user'] . '</a></span>
			                <span class="description">Shared publicly - 7:30 PM Today</span>
			              </div>

			              <div class="box-tools">
			                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
			                  <i class="fa fa-bookmark-o"></i></button>
			              </div>

			            </div>

			            <div class="box-body portfolio" data-from=' . $getAllDataApproveMessage[$i]['nama_user'] . '>
			              <h5> <strong> Title  : ' . $getAllDataApproveMessage[$i]['judul_pesan'] . ' </strong> </h5>
			              <img class="img-responsive pad" src="../dist/img/photo2.png" alt="Photo">

			              <p> <strong> ' . $getAllDataApproveMessage[$i]['isi_pesan'] . ' </strong> </p>
			            </div>

		          	</div>
		        </div>
		        ';
			}

		} else {

			$allDataApprove .= "<h1> Kosong </h1>";

		}

		$forImage .= '
			<div>
               <img src="default.jpg" id="gambar">
               <label for="exampleInputEmail1"> Upload Image Mading (*Tidak lebih dari 2 MB) </label>
               <input type="file" class="form-control fileGambar" accept="image/jpg">
            </div>';

	    $arr['jumlah_notif'] 			= $countDataNotYetAprrove;
		$arr['display_html'] 			= $outputNya;
		$arr['display_all_html'] 		= $output_all;
		$arr['count_message']			= count($getAllDataNotYetApproveMessage);
		$arr['display_html_approve']    = $allDataApprove;
		$arr['jumlah_approve'] 			= $getAllDataApproveMessage;
		$arr['upload_img'] 				= $forImage;

		echo json_encode($arr);

	}

	exit;

?>