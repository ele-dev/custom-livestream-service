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
    if(isset($_POST['oldPwd']) && isset($_POST['newPwd']) && isset($_POST['newPwdRepeat'])) {
        if($_POST['newPwd'] === $_POST['newPwdRepeat']) {
            // call the procedure to change the admin password
            $result = EnvGlobals::changePassword($_POST['oldPwd'], $_POST['newPwd']);
            if(!$result) {
                $statusLabel = "Passwort konnte nicht geändert werden!";
            } else {
                $statusLabel = "Passwort wurde erfolgreich geändert";
            }
        }
        else 
        {
            $statusLabel = "Die Felder Neues Passwort und Passwort Wiederholen sind nicht identisch!";
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
        <link rel="stylesheet" href="styles/settings.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <p><a href="php/backToList.php">Zurück zu den Aufnahmen</a></p>

        <H2>Ändern sie das Passwort für die Verwaltungsseite</H2>
            
        <center>
            <!-- password change formular -->
            <div class="styled-form">
                <form action="" method="post">
                    <p><label for="oldPwd">Aktuelles Passwort</label></p>
                    <p><input type="password" name="oldPwd" id="oldPwd"></p>
                    
                    <p><label for="newPwd">Neues Passwort</label></p>
                    <p><input type="password" name="newPwd" id="newPwd"></p>
                    
                    <p><label for="newPwdRepeat">Neues Passwort wiederholen</label></p>
                    <p><input type="password" name="newPwdRepeat" id="newPwdRepeat"></p>
                    
                    <input type="submit" value="Passwort Ändern">
                </form>

                <!-- status label -->
                <p><?php echo $statusLabel; ?></p>
            </div>
        </center>

        <!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

    </body>

</html>
