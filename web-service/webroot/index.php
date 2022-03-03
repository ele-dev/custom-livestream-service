<!DOCTYPE html>

<?php
	session_start();

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

	<!-- This is the main page, where the list of video clips is displayed -->
	<!-- From here the user can navigate to the live stream, view old clips or download them -->
	<!-- The status of the livestream and the video clip list are dynamically updated in the background using JS AJAX -->

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Streaming</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="styles/style.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>

		<!-- status bar at the top left to monitor the live stream status -->
		<p id="status-bar">
			<span class="menuBarElement">
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
		</p>

		<p id="news">
			<?php 
				require_once 'php/config.php';
				echo "<i class='fas fa-info' style='color:black;font-size:25px;'></i><b>Aktuelles: " . EnvGlobals::getNewsText() . "</b>";
			?>
		</p>
		<center>

		<!-- headline of the video clip list -->
		<H1>Aufnahmen</H1>

		<!-- show the list of recorded video clips from the past available for download and instant playback --> 
		<div id="video-list">
			<?php
				require_once 'php/pullVideoList.php';
				displayVideoClipList(false);
			?>
		</div>
	
		<!-- execute script for dynamic content update functionality -->
		<script src="js/status.js"></script>

		<!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

		</center>
	</body>
	
</html>
