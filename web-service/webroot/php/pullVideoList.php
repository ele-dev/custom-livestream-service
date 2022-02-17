<?php

	/*
		Script returns the current html formatted list of the available video clips
		to be embedded on the main page
	*/

	// include the video clip class
	require_once 'videoClipClass.php';

	function displayVideoClipList($privileged)
	{
		// Headline of the table 
		echo "<H1>Aufnahmen:</H1><table><tbody>";
		echo "<tr><th>Wochentag</th><th>Datum</th><th>Beginn</th><th>Dateigröße</th><th>Anschauen</th><th>Herunterladen</th>";
		if($privileged) {
			echo "<th>Löschen</th>";
			echo "<th>Bearbeiten</th>";
		}
		echo "</tr>";

		// Create the list of available video clips
		VideoClip::createClipList("/var/www/html/videos/*.mp4");

		// sort the list
		VideoClip::sortClips();

		// print the list
		VideoClip::printClipList($privileged);

		echo "</tbody></table>";

		return;
	}

	// check for GET parameters (to handle AJAX request)
	if(isset($_GET['priv'])) {
		if($_GET['priv'] == "yes") {
			displayVideoClipList(true);
		} else {
			displayVideoClipList(false);
		}
	}
?>
