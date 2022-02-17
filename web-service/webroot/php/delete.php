<?php
    session_start();

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

    // Attempt to delete the video file(s)
    if(!unlink($_SERVER['DOCUMENT_ROOT'] . "/videos/" . htmlspecialchars($_GET['file']))) {
        echo "<p>Failed to delete file at videos/" . $_GET['file'] . "</p>";
    } else {
        unlink($_SERVER['DOCUMENT_ROOT'] . "/videos/" . pathinfo($_GET['file'], PATHINFO_FILENAME) . ".flv");
        echo "<p>Deleted " . $_GET['file'] .  " successfully</p>";

        // Go back to admin panel after successful deletion
        header("Location: ../admin.php");
        exit;
    }
?>