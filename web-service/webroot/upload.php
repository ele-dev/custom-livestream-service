<!DOCTYPE html>

<?php 
    session_start();

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

    $statusLabel = "";

    // Check for the punlish event parameters
    if(count($_POST) > 0) {
        if(!isset($_POST['videoFile']) || !isset($_POST['recordDate']) || !isset($_POST['recordTime'])) {
            $statusLabel = "<p>Überprüfen sie ihre Eingabe auf Vollständigkeit!</p>";
        }
        else 
        {
            // validate the syntax of date and time of the recorded event
            // ...
    
            // construct desired filename 
            $newFilename = "uploaded." . date("d-m-Y", strtotime($_POST['recordDate'])) . "." . str_replace(":", "-", $_POST['recordTime']) . ".mp4";
    
            // set file move flag in database to prevent access to incomplete files (locking)
            // ...
    
            // attempt to rename and move the prepared uploaded file 
            $result = rename("/mnt/uploads/" . htmlspecialchars($_POST['videoFile']), $_SERVER['DOCUMENT_ROOT'] . "/videos/" . $newFilename);
            if(!$result) {
                $statusLabel = "<p>Fehler beim veschieben der Video Datei!</p>";
            } else {
                $statusLabel = "<p>Video Aufnahme wurde veröffentlicht</p>";
            }
    
            // unset the file move flag to release access lock
            // ...
        }
    }

    function displayRecordingsForUpload() 
    {
        // Get the list of mp4 video files in the upload directory
        $uploaded = glob("/mnt/uploads/*.mp4");

        // Display all options in the HTML form
        for($i = 0; $i < count($uploaded); $i++)
        {
            // get filename and filesize
            $filename = basename($uploaded[$i]);
            $filesize = round(filesize($uploaded[$i]) / 1000000);
            echo "<p>";
            echo "<input type='radio' id='" . $filename . "' name='videoFile' value='" . $filename . "'>";
            echo "<label for='" . $filename . "'>" . $filename . "<i class='fas fa-file-video' style='color:black;font-size:20px;'></i> (" . $filesize . " MB)</label>";
            echo "</p>";
        }

        if(count($uploaded) <= 0) {
            echo "<p>Aktuelle keine Hochgeladene Aufnahmen</p>";
        }

        return;
    }
?>

<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Verwaltung - Aufnahmen Hochladen</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/settings.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <p><a href="php/backToList.php">Zurück zu den Aufnahmen</a></p>

        <H2>Veröffentlichen sie aufgenommene Gottesdienste</H2>

        <H4>
            <p>Diese Funktion dient zum Hochladen bereits vergangener Events</p>
            <p>Sie können über SFTP Video Aufnahmen hochladen und hier Veröffentlichen, so dass sie auf der Hauptseite erscheinen</p>
        </H4>
        
        <br>
        <center>
            <div class="styled-form">
                <!-- video publisher formuar -->
                <form action="" method="post">
                    <!-- radio button selector for video file -->
                    <p><b><label>Video Aufnahme Auswählen</label></b></p>
                    <?php displayRecordingsForUpload(); ?>

                    <!-- date and time of the recorded event -->
                    <p><b><label for='idDate'>Aufnahme Datum: </label></b></p>
                    <p><input type='date' id='idDate' name='recordDate' value='<?php echo date("Y-m-d", time()); ?>'></p>

                    <p><b><label for='idTime'>Aufnahme Uhrzeit: </label></b></p>
                    <p><input type='time' id='idTime' name='recordTime' value='<?php echo date("H:i", time()); ?>'></p>
                    
                    <input type="submit" value="Aufnahme veröffentlichen">
                </form>

                <!-- status label -->
                <p><?php echo $statusLabel; ?></p>
            </div>
        </center>

        <!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

    </body>

</html>
