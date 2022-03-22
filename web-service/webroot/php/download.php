<?php
    // check for required parameter
    if(!isset($_GET["file"])) {
        header("Location: backToList.php");
        exit;
    }

    require_once "config.php";

    // ini_set("allow_url_fopen", true);

    $file = EnvGlobals::getVideoDir() . htmlspecialchars($_GET["file"]);
    var_dump($file);
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
?>