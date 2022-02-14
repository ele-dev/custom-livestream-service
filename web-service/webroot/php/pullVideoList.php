<?php

	/*
		Script returns the current html formatted list of the available video clips
		to be embedded on the main page
	*/

	// include the video clip class
	require 'videoClipClass.php';

	echo "<H1>Aufnahmen:</H1><table><tbody>";
	echo "<tr><th>Wochentag</th><th>Datum</th><th>Beginn</th><th>Dateigröße</th><th>Anschauen</th><th>Herunterladen</th></tr>";

	// Get file path to recorded streams and create the clip objects
	$vid_filePaths = glob("/var/www/html/videos/*.mp4");
	for($i = 0; $i < count($vid_filePaths); $i++)
	{
		$vid_clips[$i] = new VideoClip($vid_filePaths[$i]);
		// var_dump($vid_clips[$i]);
	}

	for($j = 0; $j < count($vid_clips); $j++)
	{
		// current file
		$file = $vid_clips[$j];
		if(($j+2) % 2 != 0) {
            echo "<tr class='white'><td>" . $file->getWeekday() . "</td><td>" 
				. $file->getRecordDate() . "</td><td>" . $file->getRecordTime() . "</td><td>" 
                . $file->getFilesize() . " MB</td><td><a href='" . htmlspecialchars("videos/" . $file->getFilename()) 
                . "'><i class='fas fa-play-circle' style='color:black;font-size:23px;'></i></a></td>
                <td><a href='" . htmlspecialchars("videos/" . $file->getFilename()) 
				. "' download='video'><i class='fas fa-download' style='color:black;font-size:23px;'></i></a></td></tr>";
		} else {
			echo "<tr class='grey'><td>" . $file->getWeekday() . "</td><td>" 
			. $file->getRecordDate() . "</td><td>" . $file->getRecordTime() . "</td><td>" 
			. $file->getFilesize() . " MB</td><td><a href='" . htmlspecialchars("videos/" . $file->getFilename()) 
			. "'><i class='fas fa-play-circle' style='color:black;font-size:23px;'></i></a></td>
			<td><a href='" . htmlspecialchars("videos/" . $file->getFilename()) 
			. "' download='video'><i class='fas fa-download' style='color:black;font-size:23px;'></i></a></td></tr>";
		}
	}

	echo "</tbody></table>";
?>
