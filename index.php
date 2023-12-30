<?php  
	
	$pindah = 0;

	if (isset($_POST['sign_in'])) {
		header('Location:tes.php');
	} else if (isset($_POST['sign_up'])) {
		if ($_POST['nama'] == '') {
			$pindah = 1;
			header('Location:index.php?err=Nama tidak boleh kosong');
		} else if ( $_POST['nama'] != '' && $_POST['email'] == '' ) {
			$pindah = 2;
			header('Location:index.php?err=Email tidak boleh kosong');
		} else if ( $_POST['password'] == '') {
			$pindah = 3;
			header('Location:index.php?err=Password tidak boleh kosong');
		} else {
			header("Location:dashboard.php");
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

	<?php if (isset($_GET['err'])): ?>
		<?php  

			if ($_GET['err'] == 'Nama tidak boleh kosong') {
				$pindah = 1;
			} elseif ($_GET['err'] == 'Email tidak boleh kosong') {
				$pindah = 2;
			}

		?>
	<?php endif ?>

<div class="container right-panel-active" id="container">
	<div class="form-container sign-up-container">
		<form action="#" method="post">
			<h1>Sign in</h1>
			<input type="email" placeholder="Email" />
			<input type="password" placeholder="Password" />
			<button type="submit" name="sign_in">Sign In</button>
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

	let pindah = "<?= $pindah; ?>"
	console.log(`${pindah}`);

	daftarButton.addEventListener('click', function(){
		container.classList.remove("right-panel-active");
		signUpContainer.style.display = "none"
		let getNama     	= document.getElementById("namanya").value
		let getEmail    	= document.getElementById("emailnya").value
		let getPassword 	= document.getElementById("passwordnya").value
		let validOrInvalid  = '';
		console.log(getNama);
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

			document.querySelector('#password').innerHTML = 'Password tidak boleh kosong'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			document.querySelector('#password').style.marginBottom = '10px'

		} else if (getEmail == '' && getPassword == '') { 

			document.querySelector('#nama').innerHTML = ''

			document.querySelector('#email').innerHTML = 'Email tidak boleh kosong'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'
			document.querySelector('#emailnya').focus()

			document.querySelector('#password').innerHTML = 'Password tidak boleh kosong'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			document.querySelector('#password').style.marginBottom = '10px'

		} else if (getNama == '' && getPassword == '') {

			document.querySelector('#email').innerHTML = ''

			document.querySelector('#nama').innerHTML = 'Nama tidak boleh kosong'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#namanya').focus()

			document.querySelector('#password').innerHTML = 'Password tidak boleh kosong'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			document.querySelector('#password').style.marginBottom = '10px'

		} else if (getNama == '' && validOrInvalid == 'invalid') {
			document.querySelector('#password').innerHTML = ''

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Nama tidak boleh kosong'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#nama').style.display = 'block'

			document.querySelector('#email').innerHTML = 'Format Email Invalid !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'

		} else if (getNama == '' && validOrInvalid == 'valid') {

			document.querySelector('#namanya').focus()
			document.querySelector('#nama').innerHTML = 'Nama tidak boleh kosong'
			document.querySelector('#nama').style.color = 'red'
			document.querySelector('#nama').style.fontSize = '11px'
			document.querySelector('#nama').style.marginRight = 'auto'
			document.querySelector('#nama').style.display = 'block'

			document.querySelector('#email').innerHTML = ''

			document.querySelector('#password').innerHTML = ''

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

			document.querySelector('#password').innerHTML = 'Password tidak boleh kosong'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			document.querySelector('#password').style.marginBottom = '10px'
		} else if (validOrInvalid == 'invalid') {

			document.querySelector('#emailnya').focus()
			document.querySelector('#nama').innerHTML 		= ''
			document.querySelector('#password').innerHTML 	= ''
			document.querySelector('#email').innerHTML = 'Format Email Invalid !'
			document.querySelector('#email').style.color = 'red'
			document.querySelector('#email').style.fontSize = '11px'
			document.querySelector('#email').style.marginRight = 'auto'
		} else if (getPassword == '') {

			document.querySelector('#passwordnya').focus()
			document.querySelector('#nama').innerHTML = ''
			document.querySelector('#email').innerHTML = ''
			document.querySelector('#password').innerHTML = 'Password tidak boleh kosong'
			document.querySelector('#password').style.color = 'red'
			document.querySelector('#password').style.fontSize = '11px'
			document.querySelector('#password').style.marginRight = 'auto'
			document.querySelector('#password').style.marginBottom = '10px'
		} else {
			document.querySelector('#nama').innerHTML = ''
			document.querySelector('#email').innerHTML = ''
			document.querySelector('#password').innerHTML = ''
			alert('ok');
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