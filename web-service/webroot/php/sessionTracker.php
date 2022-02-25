<?php 

    require_once 'config.php';

    // function to keep tracker up to date
    function updateTracker($watching, $sessionId)
    {
        
        // get a conenction handle
        $handle = EnvGlobals::getDBConnection();

        // check the session marker for activer viewer
        if($watching == true)
        {
            // check if already tracked, if not add an entry
            $query = "SELECT * FROM tbl_viewerSession WHERE id LIKE '" . htmlspecialchars($sessionId) . "'";
            if(mysqli_num_rows(mysqli_query($handle, $query)) == 0) {
                $insertQuery = "INSERT INTO tbl_viewerSession (id, lastTime) VALUES ('" . htmlspecialchars($sessionId) . "', CURRENT_TIMESTAMP())";
                $result = mysqli_query($handle, $insertQuery);
            } else {
                // when already tracked, renew the timestamp
                $updateQuery = "UPDATE tbl_viewerSession SET lastTime = CURRENT_TIMESTAMP() WHERE id LIKE '" . htmlspecialchars($sessionId) . "'";
                $result = mysqli_query($handle, $updateQuery);
            }
        }
        else
        {
            // Delete tracking entry from the list
            $delQuery = "DELETE FROM tbl_viewerSession WHERE id LIKE '" . htmlspecialchars($sessionId) . "'";
            $result = mysqli_query($handle, $delQuery);
        }

        // Additionally delete old session entries (=> lifetime exceeded without renewal)
        $lifetime = strval(EnvGlobals::getViewerSessionLifetime());
        $delQuery = "DELETE FROM tbl_viewerSession WHERE lastTime < CURRENT_TIMESTAMP() - " . $lifetime;
        $result = mysqli_query($handle, $delQuery);

        // Finally close DB connection
        mysqli_close($handle);

        return;
    }

    // Check for POST parameters (for ajax request without SESSION Cookie)
    if(isset($_GET['watching']) && isset($_GET['sessionId'])) {
        if($_GET['watching'] == "yes") {
            updateTracker(true, $_GET['sessionId']);
        } else {
            updateTracker(false, $_GET['sessionId']);
        }
    }

?>