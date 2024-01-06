<?php 

	require 'connection.php';

	if (isset($_POST['nama_user']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role_id']) && isset($_POST['kode_hrd']) ) {

		$nama_user  = htmlspecialchars($_POST['nama_user']);
		$email 	    = $_POST['email'];
 		$password   = htmlspecialchars(strtolower(stripcslashes($_POST['password'])));
		$role_id    = $_POST['role_id'];
		$getKodeHRD = $_POST['kode_hrd'];

		$kodeHRD = [
			'abc',
			'cda',
			'bca'
		];

		$options = [
            'cost' => 10,
        ];

		$passwordHash  = password_hash($password, PASSWORD_DEFAULT, $options);

		$taroKodeHRD = [];

		if ($role_id == 0) {
			$role_id = 3;
			$queryRegister     = "INSERT INTO users VALUES ('', '$nama_user', '$email', '$passwordHash', '$role_id')";
			echo $email;
			mysqli_query($conn, $queryRegister);exit;
		} else if ($role_id == 1) {
			for ($i = 0; $i < count($kodeHRD); $i++) { 
				if ($getKodeHRD == $kodeHRD[$i]) {
					$taroKodeHRD[] = "ada";
				} else {
					$taroKodeHRD[] = "ga ada";
				}
			}
		} else {
			$queryRegister     = "INSERT INTO users VALUES ('', '$nama_user', '$email', '$passwordHash', '$role_id')";
			echo $email;
			mysqli_query($conn, $queryRegister);exit;
		}

		$yes = 0;

		for ($i = 0; $i < count($taroKodeHRD); $i++) { 
			if ($taroKodeHRD[$i] == 'ada') {
				$yes = 1;
			}
		}
		
		if ($yes == 1) {

			$queryRegister     = "INSERT INTO users VALUES ('', '$nama_user', '$email', '$passwordHash', '$role_id')";

			$tampungData = [];
			$tampungData['code_error'] = 0;
			$tampungData['email'] = $email;
			echo json_encode($tampungData);
			mysqli_query($conn, $queryRegister);

		} else {

			$tampungData = [];
			$tampungData['code_error'] = 1;
			$tampungData['nama_user']  = $nama_user;
			$tampungData['email']      = $email;
			$tampungData['password']   = $password;
			$tampungData['role_id']    = $role_id;
			$tampungData['kode_hrd']   = $getKodeHRD;

			echo json_encode($tampungData);

		}

	}

?>
