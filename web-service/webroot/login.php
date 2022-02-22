<!DOCTYPE html>

<?php 
	session_start();

	// redirect if already/still logged in
	if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
		header("Location: admin.php");
		exit;
	}

	require_once 'php/config.php';

	// check if login credential parameters were passed
	if(isset($_POST['user']) && isset($_POST['password'])) {
		// Attempt to login
		if(EnvGlobals::validateLogin($_POST['user'], $_POST['password']) == true) {
			$_SESSION['loggedIn'] = true;
			header("Location: admin.php");
			exit;
		}
	}
?>

<html lang="de">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Verwaltung Login</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<link rel="stylesheet" href="styles/video-js.min.css" />
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>

		<center>
		<H1> Verwaltung Login </H1>
		<div>
			<!-- login form --> 
			<form method='post' action=''>
				<p><label for='idUser'>Benutzer: </label><input id='idUser' type='text' name='user' /></p>
				<p><label for='idPassword'>Passwort: </label><input id='idPassword' type='password' name='password' /></p>
				<p><input type='submit' value='Login' /></p>
			</form>
		</div>

		<!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

		</center>

	</body>
	
</html>
