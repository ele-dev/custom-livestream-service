<!DOCTYPE html>

<?php
	session_start();

	// Prevent unauthorized access
	if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
		header("Location: logout.php");
		exit;
	}

    // reset the active viewer marker
    if(isset($_SESSION['watching'])) {
        unset($_SESSION['watching']);
    }

	// do the session tracking
	require_once 'php/sessionTracker.php';
	
	// update the session tracker 
	updateTracker(isset($_SESSION['watching']), session_id());
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
		<link rel="stylesheet" href="styles/style.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>

		<p><H2>Administrations Oberfläche</H2></p>

		<!-- the main menu bar at the top of the admin panel --> 
		<p id='menuBar'>
			<!-- status bar at the top left to monitor the live stream status -->
			<span id="status-bar" class="menuBarElement">
				<?php 
					require_once 'php/config.php';
					if(EnvGlobals::isLive()) {
						echo "<a href='live.php'><i id='live-indicator' class='fas fa-broadcast-tower' style='color:green;font-size:25px;'></i></a>";
						echo "<b><span id='statusLabel'> Zum </span><a href='live.php'>Live Stream</a></b> <span id='viewerCount-small'></span>";
					} else {
						echo "<a href='live.php'><i id='live-indicator' class='fas fa-broadcast-tower' style='color:grey;font-size:25px;'></i></a>";
						echo "<b><span id='statusLabel'>Momentan kein </span><a href='live.php'>Live Stream</a></b>";
					}
				?>
			</span>
			
			<!-- the password change option -->
			<span class="menuBarElement">
				<a href="changePassword.php"><i class="fas fa-user-cog" style="color:black;font-size:25px;"></i><b>Passwort</b></a>
			</span>

			<!-- the hls url change option --> 
			<span class="menuBarElement">
				<a href="changeHlsUrl.php"><i class="fas fa-external-link-alt" style="color:black;font-size:25px;"></i><b>Video Player URL</b></a>
			</span>

			<!-- the news text change option -->
			<span class="menuBarElement">
				<a href="changeNewsText.php"><i class="fas fa-edit" style="color:black;font-size:25px;"></i><b>Info Lauftext</b></a>
			</span>
		</p>

		<center>

		<!-- headline of the video clip list -->
		<H1>Aufnahmen</H1>

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
			<input id='logout-btn' type='submit' value='Ausloggen'>
		</form>

	</body>
	
</html>
