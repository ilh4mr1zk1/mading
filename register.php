<?php 

	require 'connection.php';

	if (isset($_POST['nama_user']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role_id']) ) {

		$nama_user = htmlspecialchars($_POST['nama_user']);
		$email 	   = $_POST['email'];
		$password  = htmlspecialchars(strtolower(stripcslashes($_POST['password'])));

		$options = [
            'cost' => 10,
        ];

		$password  = password_hash($password, PASSWORD_DEFAULT, $options);
		$role_id   = $_POST['role_id'];

		if ($role_id == 0) {
			$role_id = 3;
		}
		
		$queryRegister     = "INSERT INTO users VALUES ('', '$nama_user', '$email', '$password', '$role_id')";

		echo $email;
		mysqli_query($conn, $queryRegister);

	}

?>
