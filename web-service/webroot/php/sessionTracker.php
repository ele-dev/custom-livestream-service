<?php 
    session_start();

    require_once 'config.php';

    // function to keep tracker up to date
    function updateTracker()
    {
        
        // get a conenction handle
        $handle = EnvGlobals::getDBConnection();

        // check the session marker for activer viewer
        if(isset($_SESSION['watching'])) 
        {
            // check if already tracked, if not add an entry
            $query = "SELECT * FROM tbl_viewerSession WHERE id LIKE '" . htmlspecialchars(session_id()) . "'";
            if(mysqli_num_rows(mysqli_query($handle, $query)) == 0) {
                $insertQuery = "INSERT INTO tbl_viewerSession (id, lastTime) VALUES ('" . htmlspecialchars(session_id()) . "', CURRENT_TIMESTAMP())";
                $result = mysqli_query($handle, $insertQuery);
            } else {
                // when already tracked, renew the timestamp
                $updateQuery = "UPDATE tbl_viewerSession SET lastTime = CURRENT_TIMESTAMP() WHERE id LIKE '" . htmlspecialchars(session_id()) . "'";
                $result = mysqli_query($handle, $updateQuery);
            }
        }
        else
        {
            // Delete tracking entry from the list
            $delQuery = "DELETE FROM tbl_viewerSession WHERE id LIKE '" . htmlspecialchars(session_id()) . "'";
            $result = mysqli_query($handle, $delQuery);
        }

        // Additionally delete old session entries (inactive for > 1 min)
        $delQuery = "DELETE FROM tbl_viewerSession WHERE lastTime < CURRENT_TIMESTAMP() - 150000";
        $result = mysqli_query($handle, $delQuery);

        // Finally close DB connection
        mysqli_close($handle);

        return;
    }

?>