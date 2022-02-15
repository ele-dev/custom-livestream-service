<!DOCTYPE html>
<html lang="de">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Player</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="stylesheet" href="video-js.min.css" />
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>
		<p><a href="index.php">Zurück zu den Aufnahmen</a></p>

		<center>
		<H1> Gottesdienst Anschauen </H1>
		<div>
			<?php 
				if(!isset($_GET['name']) || $_GET['name'] == "") {
					echo "no video clip selected!";
					exit;
				}

				require 'php/config.php';

				// Display the video player
				echo "<video width='1280' height='720' type='video/mp4' src='" . htmlspecialchars("videos/" . $_GET['name']) . "' controls>";
				echo "Der Browser kann diese Datei nicht abspielen!";
				echo "</video>";
			?>
		</div>

		<!-- include footer with impressum link and more -->
		<?php require 'php/footer.php'; ?>

		</center>

		<script src="video.min.js"></script>
		<script src="js/status.js"></script>

	</body>
	
</html>
