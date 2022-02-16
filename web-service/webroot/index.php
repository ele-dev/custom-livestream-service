<!DOCTYPE html>
<html lang="de">

	<!-- This is the main page, where the list of video clips is displayed -->
	<!-- From here the user can navigate to the live stream, view old clips or download them -->
	<!-- The status of the livestream and the video clip list are dynamically updated in the background using JS AJAX -->

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Streaming</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="style.css">
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>
		<p id="status-bar">
			<a href='live.php'>
				<i id="live-indicator" class="fas fa-broadcast-tower" style="color:grey;font-size:25px;"></i>
			</a>
			<b><span id="statusLabel"> ... </span><a href='live.php'>Live Stream</a></b>
		</p>
		<center>
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
