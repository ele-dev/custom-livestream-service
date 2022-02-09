<!DOCTYPE html>
<html lang="de">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Livestream</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="stylesheet" href="video-js.min.css" />
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>
		<p><a href="index.php">Zurück zu den Aufnahmen</a></p>

		<center>
		<H1> Gottesdienst Live Stream </H1>
		<H4>Zuschauerzahl: <span id="viewerCount">...</span></H4>		
		<div id="player-box">
			<?php 
				require 'php/config.php';

				// Only show video player when live stream is active (detected by an existing HLS playlist file .m3u8)
				if(EnvGlobals::isLive() == true) {
					echo "<video id='live-player' class='video-js vjs-default-skin' controls width='1280' height='720' poster='poster.png'>
							<p class='vjs-no-js'>javascript und HTML5 wird benötigt für Video wiedergabe</p></video>";
				} else {
					echo "<H1>Sendepause</H1>";
				}
			?>
		</div>

		<!-- include footer with impressum link and more -->
		<?php require 'php/footer.php'; ?>

		</center>

		<script src="video.min.js"></script>
		<script src="js/status.js"></script>

	</body>
	
</html>
