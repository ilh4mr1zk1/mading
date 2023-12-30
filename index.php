<?php  

	$server    = "localhost";
	$username  = "root";
	$password  = "";
	$database  = "mading_practice";
	$conn  	   = mysqli_connect($server, $username, $password, $database);

	$queryGetRole = "SELECT * FROM role";
	$execGetRole  = mysqli_query($conn, $queryGetRole);

	$roleId = [0,1,2,3];
	$role   = [
		"-- SELECT ROLE --",
		"HRD",
		"Admin",
		"Employee"
	];

	if (isset($_POST['sign_in'])) {
		header('Location:tes.php');
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
		<form action="#" method="post">
			<h1>Sign in</h1>
			<input type="email" placeholder="Email" />
			<input type="password" placeholder="Password" />
			<button type="submit" id="masuk" name="sign_in">Sign In</button>
		</form>
	</div>
	<div class="form-container sign-in-container">

		<div id="form">
			<h1>Create Account</h1>
			<input type="text" id="namanya" name="nama" placeholder="Name" />
			<small id="nama"></small>
			<input type="email" name="email" id="emailnya" placeholder="Email" />
			<small id="email"></small>
			<input type="password" id="passwordnya" name="password" placeholder="Password" />
			<small id="password"></small>
			<select name="role_id">
				<?php foreach ($roleId as $id): ?>
					<option value="<?= $id; ?>"> <?= $role[$id]; ?> </option>
				<?php endforeach ?>
			</select>
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
	
	const signUpButton = document.getElementById('signUp');
	const daftarButton = document.getElementById('daftar');
	const signInButton = document.getElementById('signIn');
	const container = document.getElementById('container');
	const signUpContainer = document.querySelector('.sign-up-container')

	daftarButton.addEventListener('click', function(){
		container.classList.remove("right-panel-active");
		signUpContainer.style.display = "none"
		let getNama     	= document.getElementById("namanya").value
		let getEmail    	= document.getElementById("emailnya").value
		let getPassword 	= document.getElementById("passwordnya").value
		let validOrInvalid  = '';
		let panjangPassword = getPassword.length
		// console.log(getNama);
		let mailFormat =  /\S+@\S+\.\S+/;
		if (getEmail.match(mailFormat)) {
			validOrInvalid = 'valid'
		} else {
			validOrInvalid = 'invalid'
		}

		console.log(validOrInvalid);

		if (getNama == '' && getEmail == '' && getPassword == '') {

			document.querySelector('#namanya').focus()

			document.querySelector('#nama').innerHTML = 'Nama tidak boleh kosong'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'

			document.querySelector('#email').innerHTML = 'Email tidak boleh kosong'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			

		} else if (getEmail == '' && getPassword == '') { 

			document.querySelector('#nama').innerHTML = ''

			document.querySelector('#email').innerHTML = 'Email tidak boleh kosong'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'
			document.querySelector('#emailnya').focus()

			document.querySelector('#password').innerHTML = 'Password cannot be empty !'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			

		} else if (getNama == '' && getPassword == '') {

			document.querySelector('#email').innerHTML = ''

			document.querySelector('#nama').innerHTML = 'Nama tidak boleh kosong'
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

			document.querySelector('#email').innerHTML = 'Format Email Invalid !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

		} else if (getNama == '' && validOrInvalid == 'valid'  && panjangPassword < 5) {

			document.querySelector('#password').innerHTML = 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Nama tidak boleh kosong'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#nama').style.display = 'block'

			document.querySelector('#email').innerHTML = ''

		} else if (getNama == '') {

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Nama tidak boleh kosong'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#nama').style.display = 'block'
			document.querySelector('#email').innerHTML = ''
			document.querySelector('#password').innerHTML = ''

		} else if (getEmail == '') {

			document.querySelector('#emailnya').focus()
			document.querySelector('#nama').innerHTML = ''
			document.querySelector('#password').innerHTML = ''
			document.querySelector('#email').innerHTML = 'Email tidak boleh kosong'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'
		} else if (validOrInvalid == 'invalid' && getPassword == '') {
			document.querySelector('#nama').innerHTML = ''

			document.querySelector('#emailnya').focus()
			document.querySelector('#email').innerHTML = 'Format Email Invalid !'
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

			document.querySelector('#email').innerHTML = 'Format Email Invalid !'
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

			document.querySelector('#email').innerHTML = 'Format Email Invalid !'
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
			
		} else if (panjangPassword < 5) {
			document.querySelector('#nama').innerHTML 		= ''
			document.querySelector('#email').innerHTML 		= ''
			document.querySelector('#password').innerHTML 	= 'Minimum 5 Character'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
		} else {
			document.querySelector('#nama').innerHTML = ''
			document.querySelector('#email').innerHTML = ''
			document.querySelector('#password').innerHTML = ''

			// $.ajax({
			// 	url 	: 'register.php',
			// 	type  	: 'post',
			// 	data    : {

			// 	}
			// })
		}
	})

	signUpButton.addEventListener('click', () => {
		signUpContainer.style.display = "block"
		container.classList.add("right-panel-active");
	});

	signInButton.addEventListener('click', () => {
		container.classList.remove("right-panel-active");
		signUpContainer.style.display = "none"
	});
</script>

</body>
</html>