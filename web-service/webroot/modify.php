<?php
    if(empty(session_id())) {
        session_start();
    }

    // Prevent unauthorized access
	if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
		header("Location: admin.php");
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

    // require_once 'php/config.php';
    require_once 'php/videoClipClass.php';

    // check for required parameters
    if(!isset($_GET['file'])) {
        header("Location: admin.php");
        exit;
    }

    // check the existence of the video file and create video file object
    $result = glob(EnvGlobals::getVideoDir() . $_GET['file']);
    if($result == false || count($result) == 0) {
        header("Location: admin.php");
        exit;
    }
    $videoFile = new VideoClip($result[0]);

    // check for optional parameters 
    // record date
    if(isset($_POST['recordDate'])) {
        // rename the video file to match the new record date
        // echo "<p>new date was passed: " . $_POST['recordDate'] . "</p>";
        $result = $videoFile->changeRecordDate($_POST['recordDate']);
        if(!$result) {
            echo "<p>Fehler: Aufnahme Datum konnte nicht geändert werden!</p>";
        } else {
            echo "<p>Aufnahme Datum wurde erfolgreich geändert</p>";
            header('Location: admin.php');
            exit;
        }
    }

    // record time
    if(isset($_POST['recordTime'])) {
        // rename the video file to match the new recod time
        // echo "<p>new time was passed: " . $_POST['recordTime'] . "</p>";
        $result = $videoFile->changeRecordTime($_POST['recordTime']);
        if(!$result) {
            echo "<p>Fehler: Aufnahme Uhrzeit konnte nicht geändert werden!</p>";
        } else {
            echo "<p>Aufnahme Uhrzeit wurde erfolgreich geändert</p>";
            header('Location: admin.php');
            exit;
        }
    }
?>

<!DOCTYPE HTML>

<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Streaming</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/settings.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
    </head>

    <body>

        <p><a href="php/backToList.php">Zurück zu den Aufnahmen</a></p>

        <H2> Clip Einstellungen ändern </H2>

        <!-- general information about the video file to be modified -->
        <div id="clip-info">
            <table>
                <tbody>
                    <tr><td><b>Dateiname:</b></td><td><?php echo $videoFile->getFilename(); ?></td></tr>
                    <tr><td><b>Dateigröße:</b></td><td><?php echo $videoFile->getFilesize(); ?> MB</td></tr>
                    <tr><td><b>Aufnahme Datum:</b></td><td><?php echo $videoFile->getRecordDate(); ?></td></tr>
                    <tr><td><b>Aufnahme Uhrzeit:</b></td><td><?php echo $videoFile->getRecordTime(); ?> Uhr</td></tr>
                </tbody>
            </table>
        </div>

        <br>
        <div>
            <H3> Aufnahme Datum bearbeiten </H3>
            <form action='' method='post'>
                <label for='idDate'>Aufnahme Datum: </label>
                <?php echo "<input type='date' id='idDate' name='recordDate' value='" . date("Y-m-d", strtotime($videoFile->getRecordDate())) . "' />"; ?>
                <input type='submit' value='Datum Speichern' /> 
            </form>
        </div>

        <br>
        <div>
            <H3> Aufnahme Uhrzeit bearbeiten </H3>
            <form action='' method='post'>
                <label for='idTime'>Aufnahme Uhrzeit: </label>
                <?php echo "<input type='time' id='idTime' name='recordTime' value='" . $videoFile->getRecordTime() . "' />"; ?>
                <input type='submit' value='Uhrzeit Speichern' />
            </form>
        </div>

        <!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

    </body>

</html>
