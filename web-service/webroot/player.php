<!DOCTYPE html>
<html lang="de">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Player</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<link rel="stylesheet" type="text/css" href="styles/player.css" />
		<link rel="stylesheet" href="styles/video-js.min.css" />
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
	<body>
		<p><a href="php/backToList.php">Zurück zu den Aufnahmen</a></p>

		<center>
		<H1> Gottesdienst Anschauen </H1>
		<div>
			<?php 
				// check the required parameter to select the right video file
				if(!isset($_GET['name']) || $_GET['name'] == "") {
					echo "no video clip selected!";
					exit;
				}

				require_once 'php/config.php';

				// display the HTML5 video player
				echo "<video class='responsive-video' width='1280' height='720' type='video/mp4' src='" . htmlspecialchars("videos/" . $_GET['name']) . "' controls>";
				echo "Der Browser kann diese Datei nicht abspielen!";
				echo "</video>";
			?>
		</div>

		<!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

		</center>

	</body>
	
</html>
