<!DOCTYPE HTML>

<?php
    session_start();

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
    if(isset($_POST['newUrl'])) {
        // validate the string pattern
        $temp = explode("/", $_POST['newUrl']);
        $numSeg = count($temp);
        if($numSeg == 5 && $temp[3] == "hls" && ($temp[0] == "http:" || $temp[0] == "https:") && $temp[1] == "") {
            // call the procedur to change the url
            $result = EnvGlobals::changeStreamUrl($_POST['newUrl']);
            if(!$result) {
                $statusLabel = "Unbekannter Fehler: Die HLS URL konnte nicht geändert werden!";
            } else {
                $statusLabel = "Die HLS URL wurde erfolgreich geändert.";
            }
        } 
        else 
        {
            $statusLabel = "Fehler: Ungültige Eingabe. Überprüfen sie die URL auf Gültigkeit!";
        }
    }
?>

<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Verwaltung</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="styles/style.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <p><a href="php/backToList.php">Zurück zu den Aufnahmen</a></p>

        <H2>Ändern sie die HTTP(S) URL, die vom Live Player abgespielt wird</H2>

        <H4><p>Diese Funktion kann nützlich sein für technische Tests oder um auf</p>
        <p>einen alternativen Stream Schlüssel umzusteigen (z.B. als Schutz gegen Komprimittierung).</p>
        <p><b>Wichtig: Ändern sie die Einstellung nur wenn sie wissen was sie tuen! </p>
        <p>Bei falscher Konfiguration können die Nutzer keine Live Streams mehr sehen! </p></b></H4>
        
        <br>
        <center>
            <!-- hls url editor formuar -->
            <form action="" method="post">
                <p><label for="newUrl">Neue HTTP(S) HLS URL eingeben:</label></p>
                <p><input type="text" name="newUrl" id="newUrl" value="<?= EnvGlobals::getStreamUrl(); ?>"></p>
                
                <input type="submit" value="HLS URL Ändern">
            </form>

            <!-- status label -->
            <p><?php echo $statusLabel; ?></p>
        </center>

        <!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

    </body>

</html>
