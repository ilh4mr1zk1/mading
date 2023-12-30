<?php 

	require 'connection.php';

	if (isset($_POST['nama_user']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role_id']) ) {

		$nama_user = htmlspecialchars($_POST['nama_user']);
		$email 	   = $_POST['email'];
		$password  = md5($_POST['password']);
		$role_id   = $_POST['role_id'];

		if ($role_id == 1) {
			$role_id = 4;
		}
		
		$queryRegister     = "INSERT INTO users VALUES ('', '$nama_user', '$email', '$password', '$role_id')";

		return mysqli_query($conn, $queryRegister);

	}

?>
