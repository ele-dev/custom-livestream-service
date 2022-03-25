<?php
    if(empty(session_id())) {
		session_start();
	}

    // Prevent unauthorized access
	if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
		header("Location: logout.php");
		exit;
	}

    // Now check for the required GET parameter
    if(!isset($_GET['file'])) {
        echo "No filename given!";
        exit;
    }

    require_once "config.php";

    // Attempt to delete the video file(s)
    if(!unlink(EnvGlobals::getVideoDir() . htmlspecialchars($_GET['file']))) {
        echo "<p>" . $_GET['file'] . " konnte nicht gelöscht werden!</p>";
    } else {
        unlink(EnvGlobals::getVideoDir() . pathinfo($_GET['file'], PATHINFO_FILENAME) . ".flv");
        echo "<p>" . $_GET['file'] .  " wurde erfolgreich gelöscht.</p>";

        // Go back to admin panel after successful deletion
        header("Location: ../admin.php");
        exit;
    }
?>
