<?php

	/*
		This little script just prints status information
		in plain text format for easy processing in any scripts (Javascript AJAX)
		format pattern: <key>::<value>::<key>::<value>:: ...
	*/

	require_once 'config.php';
	
	echo "livestream::";
	if(EnvGlobals::isLive() == true) {
		echo "active";
	} else {
		echo "inactive";
	}
	echo "::stream-url::" . EnvGlobals::getStreamUrl();
?>
