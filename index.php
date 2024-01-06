<?php  

	require_once "dbconfig.php";

	// Cek status login user jika ada session
	if ($user->isLoggedIn()) {
	    header("location: /mading/dashboard"); //redirect ke index
	}

	function getName($n) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	 
	    for ($i = 0; $i < $n; $i++) {
	        $index = rand(0, strlen($characters) - 1);
	        $randomString .= $characters[$index];
	    }
	 
	    return $randomString;
	}

	$role   = [
		0 => "-- SELECT ROLE --",
		1 => "HRD",
		2 => "Admin",
		3 => "Employee"
	];

	$error      = '';
	$emailError = '';
	$kode 		= '';


	//jika ada data yg dikirim
	if (isset($_POST['sign_in'])) {
	    $email 	  = $_POST['email'];
	    $password = $_POST['password'];

	    // Proses login user
	    if ($user->login($email, $password)) {
	        header("location: /mading/dashboard");
	    } else {
	        // Jika login gagal, ambil pesan error
	        $error      = $user->getLastError();
	        $emailError = $user->getEmailUser();
	        $kode 		= $user->getCodeUser();
	        if ($kode == 1) {
	        	echo "<script>alert('$error');</script>";
	        } else if ($kode == 2) {
	        	echo "<script>alert('$error');</script>";
	        }
	    }
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>  </title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div class="container right-panel-active" id="container">
	<div class="form-container sign-up-container">
		<?php if ($emailError !== '' || $error !== ''): ?>
			<form action="#" method="post">
				<h1>Sign in</h1>
				<input type="email" class="email_signin" name="email" id="email_signin" value="<?= $emailError; ?>" placeholder="Email" />
				<input type="password" id="password_signin" class="password_signin" name="password" placeholder="Password" />
				<button type="submit" id="masuk" name="sign_in">Sign In</button>
			</form>
		<?php endif ?>

		<form action="#" method="post">
			<h1>Sign in</h1>
			<input type="email" name="email" id="email_signin" placeholder="Email" />
			<!-- <small id="email" style="display: none;"></small> -->
			<input type="password" id="password_signin" name="password" placeholder="Password" />
			<button type="submit" id="masuk" name="sign_in">Sign In</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<!--  pattern=".*@gmail\.com" required -->
		<div id="form">
			<h1>Create Account</h1>
			<input type="text" id="namanya" name="nama" placeholder="Name" />
			<small id="nama"></small>
			<input type="email" name="email" pattern=".*@gmail\.com" required id="emailnya" placeholder="youremail@gmail.com" />
			<small id="email"></small>
			<input type="password" id="passwordnya" name="password" placeholder="Password" />
			<small id="password"></small>
			<select id="roleidnya" name="role_id">
				<?php foreach ($role as $id => $nama_role): ?>
					<option value="<?= $id; ?>"> <?= $nama_role; ?> </option>
				<?php endforeach ?>
			</select>
			<input type="text" id="buat_hrd" name="buat_hrd" placeholder="Code HRD" />
			<small id="buat_hrd_2"></small>
			<button type="submit" id="daftar" name="sign_up">Sign Up</button>
		</div>

	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Hello, Friend!</h1>
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signIn">Sign Up</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				
				<button class="ghost" id="signUp">Sign In</button>
			</div>
		</div>
	</div>
</div>

<script src="js/jquery-3.7.1.js"></script>
<script type="text/javascript">
	document.querySelector('#email_signin').focus()
	const signUpButton 		= document.getElementById('signUp');
	const daftarButton		= document.getElementById('daftar');
	const signInButton		= document.getElementById('signIn');
	const container 		= document.getElementById('container');
	const signUpContainer 	= document.querySelector('.sign-up-container')
	const formEmailLogin 	= document.getElementById('email_signin');
	
	const formNameSignUp 	 = document.getElementById('namanya');
	const formEmailSignUp 	 = document.getElementById('emailnya');
	const formPasswordSignUp = document.getElementById('passwordnya');
	const formRoleIdSignUp   = document.getElementById('roleidnya');
	const formCodeHRDSignUp  = document.getElementById('buat_hrd');

	let formEmailLogins 	= document.querySelector('.email_signin');
	let formPasswordLogins 	= document.querySelector('.password_signin');

	let kode 			= `<?= $kode; ?>`

	if (kode == 1) {
		formEmailLogins.focus()
	} else if (kode == 2) {
		formPasswordLogins.value = ''
		formPasswordLogins.focus()
	}
	console.log(kode);
	$("#buat_hrd").hide()
	$("#buat_hrd_2").hide()

	let isiRoleId = 0

	$("#roleidnya").change(function(){
  		let isi = formRoleIdSignUp.value;
	  	if (isi == 1) {
	  		isiRoleId = isi
  			console.log(isiRoleId);
			formRoleIdSignUp.style.marginBottom = '10px'
		  	$("#buat_hrd").show()
			$("#buat_hrd_2").show()
			let elementBuatHrd2 = document.querySelector('#buat_hrd_2')
		  	elementBuatHrd2.style.marginBottom = '15px'
		} else {
			isiRoleId = isi
		  	$("#buat_hrd").hide()
		  	formRoleIdSignUp.style.marginBottom = '15px'
			$("#buat_hrd_2").hide()
			document.querySelector('#buat_hrd_2').innerHTML = ''
  			console.log(isiRoleId);
		}
	}); 

	daftarButton.addEventListener('click', function(){
		container.classList.remove("right-panel-active");
		signUpContainer.style.display = "none"
		let getNama     	= document.getElementById("namanya").value
		let getEmail    	= document.getElementById("emailnya").value
		let getPassword 	= document.getElementById("passwordnya").value
		let getRoleId 		= document.getElementById("roleidnya").value
		let validOrInvalid  = '';
		let panjangPassword = getPassword.length

		let mailFormat =  /\S+@\S+\.\S+/;
		// if (getEmail.match(mailFormat)) {
		// 	validOrInvalid = 'valid'
		// } else {
		// 	validOrInvalid = 'invalid'
		// }

		if (/^([A-Za-z0-9_\-\.])+\@([gmail|GMAIL])+\.(com)$/.test(getEmail)) {
		    validOrInvalid = 'valid'
		} else {
			validOrInvalid = 'invalid'
		}

		console.log(validOrInvalid);

		if (getNama == '' && getEmail == '' && getPassword == '' && formRoleIdSignUp.value == 0) {

			document.querySelector('#namanya').focus()

			document.querySelector('#nama').innerHTML = 'Name cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'

			document.querySelector('#email').innerHTML = 'Email cannot be empty !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			

		} else if (getEmail == '' && getPassword == '') { 

			document.querySelector('#nama').innerHTML = ''

			document.querySelector('#email').innerHTML = 'Email cannot be empty !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'
			document.querySelector('#emailnya').focus()

			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			

		} else if (getNama == '' && validOrInvalid == 'invalid' && getPassword == '') {

			document.querySelector('#email').innerHTML = ''

			document.querySelector('#nama').innerHTML = 'Name cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#namanya').focus()

			document.querySelector('#email').innerHTML = 'Format Email Must be @gmail.com'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

		} else if (getNama == '' && getEmail == '' && panjangPassword < 5) {

			document.querySelector('#nama').innerHTML = 'Name cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#namanya').focus()

			document.querySelector('#email').innerHTML = 'Email cannot be empty !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

			document.querySelector('#password').innerHTML = 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

		} else if (getNama == '' && getPassword == '') {

			document.querySelector('#email').innerHTML = ''

			document.querySelector('#nama').innerHTML = 'Name cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#namanya').focus()

			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			

		} else if (getNama == '' && validOrInvalid == 'invalid' && panjangPassword < 5) {
			document.querySelector('#password').innerHTML = 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Name Cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#nama').style.display = 'block'

			document.querySelector('#email').innerHTML = 'Format Email Must be @gmail.com'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

		} else if (getNama == '' && validOrInvalid == 'valid' && panjangPassword < 5) {

			document.querySelector('#password').innerHTML = 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Name cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#nama').style.display = 'block'

			document.querySelector('#email').innerHTML = ''

		} else if (getNama == '' && validOrInvalid == 'invalid' && panjangPassword >= 5) {

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Name cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'

			document.querySelector('#email').innerHTML = 'Format Email Must be @gmail.com'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

			document.querySelector('#password').innerHTML = ''

		} else if (getNama == '') {

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Name cannot be empty !'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#nama').style.display = 'block'
			document.querySelector('#email').innerHTML = ''
			document.querySelector('#password').innerHTML = ''
			document.querySelector('#buat_hrd_2').innerHTML = ''

		} else if (getNama !== '' && getEmail == '' && panjangPassword < 5) {

			document.querySelector('#emailnya').focus()

			document.querySelector('#nama').innerHTML = ''
			
			document.querySelector('#email').innerHTML = 'Email cannot be empty !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

			document.querySelector('#password').innerHTML = 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

		} else if (getEmail == '') {

			document.querySelector('#emailnya').focus()
			document.querySelector('#nama').innerHTML = ''
			document.querySelector('#password').innerHTML = ''
			document.querySelector('#buat_hrd_2').innerHTML = ''
			document.querySelector('#email').innerHTML = 'Email cannot be empty !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

		} else if (validOrInvalid == 'invalid' && getPassword == '') {
			document.querySelector('#nama').innerHTML = ''

			document.querySelector('#emailnya').focus()
			document.querySelector('#email').innerHTML = 'Format Email Must be @gmail.com'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			
		} else if (validOrInvalid == 'invalid' && panjangPassword < 5) {

			document.querySelector('#emailnya').focus()
			document.querySelector('#nama').innerHTML 		= ''

			document.querySelector('#email').innerHTML = 'Format Email Must be @gmail.com'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'
			document.querySelector('#password').innerHTML = 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
		} else if (validOrInvalid == 'invalid') {

			document.querySelector('#emailnya').focus()
			document.querySelector('#nama').innerHTML 		= ''
			document.querySelector('#password').innerHTML   = ''
			document.querySelector('#buat_hrd_2').innerHTML = ''

			document.querySelector('#email').innerHTML = 'Format Email Must be @gmail.com'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

		} else if (getPassword == '') {

			document.querySelector('#passwordnya').focus()
			document.querySelector('#nama').innerHTML = ''
			document.querySelector('#email').innerHTML = ''
			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

			document.querySelector('#buat_hrd_2').innerHTML = ''
			
		} else if (panjangPassword < 5) {

			document.querySelector('#passwordnya').focus()

			document.querySelector('#nama').innerHTML 		= ''
			document.querySelector('#email').innerHTML 		= ''
			document.querySelector('#password').innerHTML 	= 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

			document.querySelector('#buat_hrd_2').innerHTML = ''

		} else if (isiRoleId == 1) {

			if (formCodeHRDSignUp.value == '') {
				alert('Code HRD cannot be empty !');
				formCodeHRDSignUp.focus()
				document.querySelector('#nama').innerHTML = ''
				document.querySelector('#email').innerHTML = ''
				document.querySelector('#password').innerHTML = ''
				document.getElementById("namanya").value = getNama
				document.getElementById("emailnya").value = getEmail
				document.getElementById("passwordnya").value = getPassword

				document.querySelector('#buat_hrd_2').innerHTML = 'Please Insert Code for HRD !'
				document.querySelector('#buat_hrd_2').style.color = 'red'
				document.querySelector('#buat_hrd_2').style.fontSize = '11px'
				document.querySelector('#buat_hrd_2').style.marginRight = 'auto'

			} else {

				formNameSignUp.value     = ''
				formEmailSignUp.value    = ''
				formPasswordSignUp.value = '' 
				document.querySelector('#nama').innerHTML = ''
				document.querySelector('#email').innerHTML = ''
				document.querySelector('#password').innerHTML = ''

				$.ajax({
					url 	: 'register.php',
					type  	: 'post',
					data    : {
						nama_user : getNama,
						email 	  : getEmail,
						password  : getPassword,
						role_id   : getRoleId
					},
					success:function(data) {
						alert("Success Register");
						signUpButton.click()
						formEmailLogin.value = data
						// console.log(data);
					}
				})

			}

		} else {

			formNameSignUp.value     = ''
			formEmailSignUp.value    = ''
			formPasswordSignUp.value = '' 
			document.querySelector('#nama').innerHTML = ''
			document.querySelector('#email').innerHTML = ''
			document.querySelector('#password').innerHTML = ''

			$.ajax({
				url 	: 'register.php',
				type  	: 'post',
				data    : {
					nama_user : getNama,
					email 	  : getEmail,
					password  : getPassword,
					role_id   : getRoleId
				},
				success:function(data) {
					alert("Success Register");
					signUpButton.click()
					formEmailLogin.value = data
					// console.log(data);
				}
			})

		}
	})

	signUpButton.addEventListener('click', () => {
		signUpContainer.style.display = "block"
		container.classList.add("right-panel-active");
		formNameSignUp.value     = ''
		formEmailSignUp.value    = ''
		formPasswordSignUp.value = ''
		formRoleIdSignUp.value   = 0
		formRoleIdSignUp.style.marginBottom = '15px'
		formCodeHRDSignUp.value  = ''
		formCodeHRDSignUp.style.display = 'none'
		document.querySelector("#buat_hrd_2").style.display = 'none'
		document.querySelector('#nama').innerHTML = ''
		document.querySelector('#email').innerHTML = ''
		document.querySelector('#password').innerHTML = ''
		document.querySelector('#buat_hrd_2').innerHTML = ''

		document.querySelector('#email_signin').focus()
	});

	signInButton.addEventListener('click', () => {
		container.classList.remove("right-panel-active");
		signUpContainer.style.display = "none"
		document.querySelector('#namanya').focus()
	});
</script>

</body>
</html>