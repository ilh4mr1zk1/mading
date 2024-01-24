<?php  

	require_once "../dbconfig.php";

	if (!$user->isLoggedIn()) {  
        header("location:/mading"); //Redirect ke halaman login  
    } 

    //  if (isset($_POST['title']) && isset($_POST['image']) && isset($_POST['announcement']) && isset($_POST['status_approve']) && isset($_POST['user_id']) && isset($_POST['formNya'])) {

	// 	$message_title 	= $_POST['title'];
	// 	$message_info 	= $_POST['announcement'];
	// 	$image 			= $_POST['image'];
	// 	$status_approve = $_POST['status_approve'];
	// 	$user_id        = $_POST['user_id'];
	// 	$namaFile 		= $_POST['form'];
	// 	var_dump($namaFile);exit;

	// 	$user->insertDataMessageApprove($message_title, $message_info, $image, $status_approve, $user_id);

	// 	$arr = [];

    //     $arr['message_title']   = $message_title;
    //     $arr['message_info']    = $message_info;
    //     $arr['status_approve']  = $status_approve ;
    //     $arr['user_id']         = $user_id;

	// 	echo json_encode($arr);

	// } 

    // print_r($_POST);exit;
	$arr 				 = [];
	$arr['title'] 		 = $_POST['title_ann'];
	$arr['announcement'] = $_POST['announcement'];
	$arr['nama_gambar']  = $_FILES['banner']['name'];
	$arr['tmp_name']  	 = $_FILES['banner']['tmp_name'];
	$arr['status']  	 = "Success Add Data";

	$namaFile       = $_FILES['banner']['name'];
	$tmpName 		= $_FILES['banner']['tmp_name'];

	$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];

	$ekstensiGambar = explode('.', $namaFile);
	$ekstensiGambar = strtolower(end($ekstensiGambar) );

	if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
		echo "<script>
				alert('Yang Anda Upload Bukan File Gambar !');
			  </script>";
		return false;  
	}

	$namaFileBaru 	= uniqid();
	$namaFileBaru  .= '.';
	$namaFileBaru  .= $ekstensiGambar;
	
	$user->insertDataMessageApprove($arr['title'], $arr['announcement'], $namaFileBaru, 1, $_SESSION['user_id']);

	move_uploaded_file($tmpName, '../img/' . $namaFileBaru);

	echo json_encode($arr);

?>