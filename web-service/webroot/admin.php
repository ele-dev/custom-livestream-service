<!DOCTYPE html>

<?php
	session_start();

	// Prevent unauthorized access
	if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
		header("Location: logout.php");
		exit;
	}

	echo "<p><H2> Admin Panel </H2></p>";
?>

<html lang="de">

	<!-- This is the administration page, where the list of video clips is displayed -->
	<!-- From here the admins can delete clips, change settings and more -->
	<!-- The status of the livestream is updated dynamically in the background using JS AJAX -->

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Verwaltung</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="style.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>

		<!-- status bar at the top left to monitor the live stream status -->
		<p id="status-bar">
			<?php 
				require_once 'php/config.php';
				if(EnvGlobals::isLive()) {
					echo "<a href='live.php'><i id='live-indicator' class='fas fa-broadcast-tower' style='color:green;font-size:25px;'></i></a>";
					echo "<b><span id='statusLabel'> Zum </span><a href='live.php'>Live Stream</a></b>";
				} else {
					echo "<a href='live.php'><i id='live-indicator' class='fas fa-broadcast-tower' style='color:grey;font-size:25px;'></i></a>";
					echo "<b><span id='statusLabel'> Momentan kein </span><a href='live.php'>Live Stream</a></b>";
				}
			?>
		</p>

		<center>

		<!-- show the list of video clips with advanced access and options for admins -->
		<div id="admin-video-list">
			<?php
				require_once 'php/pullVideoList.php';
				displayVideoClipList(true);
			?>
		</div>

		<!-- execute script for autorefreshed live stream status display -->
		<script src="js/status.js"></script>

		<!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

		</center>

		<!-- logout button -->
		<form action='logout.php' method='post'>
			<input type='submit' value='Ausloggen'>
		</form>

	</body>
	
</html>
