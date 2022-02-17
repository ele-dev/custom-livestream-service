<?php
    /*
        Just a small script that handles the redirection from player or live page
        back to the clip list overview (different for admins and users)
    */

    session_start();

    // Check if admin is logged in
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
        header("Location: ../admin.php");
        exit;
    }

    // For users
    header("Location: ../index.php");
    exit;

?>