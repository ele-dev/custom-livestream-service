<?php
    // check for required parameter
    if(!isset($_GET["file"])) {
        header("Location: backToList.php");
        exit;
    }

    require_once "config.php";

    $file = EnvGlobals::getVideoDir() . htmlspecialchars($_GET["file"]);
    var_dump($file);
    if (file_exists($file)) {
        $mimeType = mime_content_type($file);
        var_dump($mimeType);
        header('Content-Description: File Transfer');
        if($mimeType != false) {
            header('Content-Type: ' . $mimeType);
        }
        header('Content-Disposition: attachment; filename=video.mp4');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
?>