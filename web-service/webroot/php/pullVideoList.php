<?php

	/*
		Script returns the current html formatted list of the available video clips
		to be embedded on the main page
	*/

	// include the video clip class
	require 'videoClipClass.php';

	// Headline of the table 
	echo "<H1>Aufnahmen:</H1><table><tbody>";
	echo "<tr><th>Wochentag</th><th>Datum</th><th>Beginn</th><th>Dateigröße</th><th>Anschauen</th><th>Herunterladen</th></tr>";

	// Create the list of available video clips
	VideoClip::createClipList("/var/www/html/videos/*.mp4");

	// sort the list
	VideoClip::sortClips();

	// print the list
	VideoClip::printClipList();

	echo "</tbody></table>";
?>
