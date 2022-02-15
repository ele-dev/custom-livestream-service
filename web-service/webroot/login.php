<!DOCTYPE html>
<html lang="de">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Verwaltung Login</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="stylesheet" href="video-js.min.css" />
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>
		<p><a href="index.php">Zurück zur Startseite</a></p>

		<center>
		<H1> Verwaltung Login </H1>
		<div>
			<form method='post' action=''>
				<p><label for='idUser'>Benutzer: </label><input id='idUser' type='text' name='user' /></p>
				<p><label for='idPassword'>Passwort: </label><input id='idPassword' type='password' name='password' /></p>
				<p><input type='submit' value='Login' /></p>
			</form>
		</div>

		<!-- include footer with impressum link and more -->
		<?php require 'php/footer.php'; ?>

		</center>

	</body>
	
</html>
