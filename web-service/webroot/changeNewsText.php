<?php
    if(empty(session_id())) {
		session_start();
	}

    // Prevent unauthorized access
	if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
		header("Location: logout.php");
		exit;
	}

    // reset the active viewer marker
    if(isset($_SESSION['watching'])) {
        unset($_SESSION['watching']);
    }

	// do the session tracking
	require_once 'php/sessionTracker.php';
	
    // update the session tracker
	updateTracker(isset($_SESSION['watching']), session_id());

    require_once 'php/config.php';

    $statusLabel = "";

    // check for the POST parameters of the formular
    if(isset($_POST['newText'])) {
        // call the procedur to change the news text
        $result = EnvGlobals::changeNewsText($_POST['newText']);
        if(!$result) {
            $statusLabel = "Unbekannter Fehler: Der Lauftext konnte nicht geändert werden!";
        } else {
            $statusLabel = "Der Anzeige Text wurde erfolgreich geändert.";
        }
    }
?>

<!DOCTYPE HTML>

<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Verwaltung</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/settings.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <p><a href="php/backToList.php">Zurück zu den Aufnahmen</a></p>

        <H2>Ändern sie den Lauftext der bei Aktuell angezeigt wird</H2>

        <H4><p>Hier können Info Texte eingestellt werden</p></H4>
        
        <br>
        <center>
            <div class="styled-form">
                <!-- news text editor formuar -->
                <form action="" method="post">
                    <p><label for="newText">Neuen Lauftext eingeben</label></p>
                    <p><input type="text" name="newText" id="newText" value="<?= EnvGlobals::getNewsText(); ?>"></p>
                    
                    <input type="submit" value="Lauftext Anzeige Ändern">
                </form>

                <!-- status label -->
                <p><?php echo $statusLabel; ?></p>
            </div>
        </center>

        <!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

    </body>

</html>
