<?php

	/*
		Script returns the current html formatted list of the available video clips
		to be embedded on the main page
	*/

	// include the video clip class
	require 'videoClipClass.php';

	echo "<H1>Aufnahmen:</H1><table><tbody>";
	echo "<tr><th>Wochentag</th><th>Datum</th><th>Beginn</th><th>Dateigröße</th><th>Anschauen</th><th>Herunterladen</th></tr>";

	// Function to generate Weekday names from indexes
	function getWeekdayStr($dayIdx)
	{
		if($dayIdx == "%0")
			return "Sonntag";
		else if($dayIdx == "%1")
			return "Montag";
		else if($dayIdx == "%2")
			return "Dienstag";
		else if($dayIdx == "%3")
			return "Mittwoch";
		else if($dayIdx == "%4")
			return "Donnerstag";
		else if($dayIdx == "%5")
			return "Freitag";
		else 
			return "Samstag";
	}

	// Get file path to recorded streams
	$vid_filePaths = glob("/var/www/html/videos/*.mp4");
	for($i = 0; $i < count($vid_filePaths); $i++) {
		$temp = explode(".", $vid_filePaths[$i]);
		$recordDate = str_replace("-", ".", $temp[1]);
		$recordTime = str_replace("-", ":", $temp[2]);
		$weekDay = getWeekdayStr(date("%w", strtotime($recordDate)));
		// echo " " . $weekDay;
		if(($i+2) % 2 != 0) {
            echo "<tr class='white'><td>" . $weekDay . "</td><td>" . $recordDate . "</td><td>" . $recordTime . "</td><td>" 
                . round(filesize($vid_filePaths[$i])/1000000) . " MB</td><td><a href='" . htmlspecialchars("videos/" . basename($vid_filePaths[$i])) 
                . "'><i class='fas fa-play-circle' style='color:black;font-size:23px;'></i></a></td>
                <td><a href='" . htmlspecialchars("videos/" . basename($vid_filePaths[$i])) . "' download='video'><i class='fas fa-download' style='color:black;font-size:23px;'></i></a></td></tr>";
		} else {
			echo "<tr class='grey'><td>" . $weekDay . "</td><td>" . $recordDate . "</td><td>" . $recordTime . "</td><td>" 
			. round(filesize($vid_filePaths[$i])/1000000) . " MB</td><td><a href='" . htmlspecialchars("videos/" . basename($vid_filePaths[$i])) 
			. "'><i class='fas fa-play-circle' style='color:black;font-size:23px;'></i></a></td>
			<td><a href='" . htmlspecialchars("videos/" . basename($vid_filePaths[$i])) . "' download='video'><i class='fas fa-download' style='color:black;font-size:23px;'></i></a></td></tr>";
		}
	}

	echo "</tbody></table>";
?>
