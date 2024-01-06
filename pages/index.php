<?php  

	require_once "../dbconfig.php";

	// Cek status login user jika ada session
	if ($user->isLoggedIn()) {
	    header("location: /mading/dashboard"); //redirect ke index
	}

?>