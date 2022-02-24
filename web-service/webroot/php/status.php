<?php

	/*
		This little script just prints status information
		in plain text format for easy processing in any scripts (Javascript AJAX)
		format pattern: <key>::<value>::<key>::<value>:: ...
	*/

	require_once 'config.php';
	
	$viewerCount = 0;

	echo "livestream::";
	if(EnvGlobals::isLive() == true) {
		echo "active";
		// Get viewer count by counting active session entries
		$handle = EnvGlobals::getDBConnection();

		$query = "SELECT id FROM tbl_viewerSession";
		$viewerCount = mysqli_num_rows(mysqli_query($handle, $query));

	} else {
		echo "inactive";
	}
	echo "::stream-url::" . EnvGlobals::getStreamUrl();
	echo "::viewerCount::" . $viewerCount;
?>
