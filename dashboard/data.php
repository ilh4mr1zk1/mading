<?php  

	require_once "../dbconfig.php";

	if (!$user->isLoggedIn()) {  
        header("location:/mading"); //Redirect ke halaman login  
    }  

	$countDataMessage = $user->countDataMessage();

	$status_approve = "";

	if (isset($_POST['message_title']) && isset($_POST['message_info']) && isset($_POST['status_approve']) && isset($_POST['id']) ) {
		
		$messsage_title = $_POST['message_title'];
		$message_info 	= $_POST['message_info'];
		$status_approve = $_POST['status_approve'];
		$id 		    = $_POST['id'];

		$user->updateData($messsage_title, $message_info, $status_approve, $id);
		
		$checkData = $user->updateData($messsage_title, $message_info, $status_approve, $id);

		$getDataNoApprove = $user->getDataNoApprove();

		$arr = [];

		$countDataNotYetAprrove = $user->countDataMessage();

        $arr['id']              = $getDataNoApprove['id'];
        $arr['message_title']   = $getDataNoApprove['message_title'];
        $arr['message_info']    = $getDataNoApprove['message_info'];
        $arr['status_approve']  = $getDataNoApprove['status_approve'];
        $arr['not_yet_approve'] = $countDataNotYetAprrove;

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

	} else {

	 	$getDataMessage   		= $user->getDataMessage(1);
	 	$countDataNotYetAprrove = $user->countDataMessage();

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
				<li class="show_data" data-toggle="modal" data-id="'. $row['message_id'] .'" data-from="'. $row['nama_user'] .'" data-title="'. $row['judul_pesan'] .'" data-main="'. $row['isi_pesan'] .'" data-target="modal-default">
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

	    $arr['jumlah_notif'] = $countDataNotYetAprrove;
		$arr['display_html'] = $outputNya;

		echo json_encode($arr);

	}

	// $hasilUpdate = $user->getDataMessage()

	exit;

?>