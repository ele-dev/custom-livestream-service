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


?>

<!DOCTYPE HTML>

<html lang="de">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset='utf-8'>
		<title>Gottesdienst Verwaltung - Statistiken</title>
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="styles/style.css">
		<!-- external script required to use the fontawsome icon pack (ver. 5) --> 
		<script src="https://kit.fontawesome.com/48d181da71.js" crossorigin="anonymous"></script>
	</head>
	
    <body>

        <p><a href="php/backToList.php">Zurück zu den Aufnahmen</a></p>

        <H1>Basis Statistiken</H1>
        <hr>

        <div>
            <table>
                <tbody>
                    <tr>
                        <th>Seite</th><th>Heute</th><th>Gestern</th><th>Woche (Mo-So)</th><th>Monat</th><th>Jahr</th><th>Gesamt</th><th>Rekord</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <p><a href="">Zu den Tages Statistiken</a></p>

    	<!-- include footer with impressum link and more -->
		<?php require_once 'php/footer.php'; ?>

    </body>

</html>


