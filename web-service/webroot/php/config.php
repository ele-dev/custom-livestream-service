<?php
    /*
        This file holds all the environment variables which are 
        relevant for software deploy.
        all global configs should be set here and retrieved from here
        using static functions
    */

    class EnvGlobals
    {
        private static $hls_http_stream = "http://<hostIP>:8082/hls/test.m3u8";

	    // For production deploy (behind reverse proxy)
        // private static $hls_http_stream = "https://<subdomain>.<domain>:4434/hls/test.m3u8";
        private static $vid_player_width = 1280;
        private static $vid_player_height = 720;
        private static $db_host = "dbHost";
        private static $db_user = "dbUser";
        private static $db_pass = "db-pass";
        private static $db_databaseName = "testDB";

        public static function getDBConnection() 
        {
            // return mysqli connection handle
            $dbCon = mysqli_connect(self::$db_host, self::$db_user, self::$db_pass, self::$db_databaseName);

            return $dbCon;
        }

        public static function isLive()
        {
            $result = fopen(self::$hls_http_stream, "rb");
            if($result == false) {
                return false;
            } else {
                return true;
            }
        }

        public static function getStreamUrl()
        {
            return self::$hls_http_stream;
        }

        public static function getPlayerWidth() 
        {
            return self::$vid_player_width;
        }

        public static function getPlayerHeight() 
        {
            return self::$vid_player_height;
        }

        public static function validateLogin($user, $pass) 
        {
            // check if the credentials match the database entry
            $handle = self::getDBConnection();
            
            $result = mysqli_query($handle, "SELECT * FROM tbl_envVar WHERE name LIKE 'admin-user' AND value LIKE '" . htmlspecialchars($user) . "'");
            // in case of wrong username 
            if(mysqli_num_rows($result) != 1) {
                return false;
            }

            // generate SHA-256 hash from plain text password
            $passHash = hash("sha256", $pass);
            $result = mysqli_query($handle, "SELECT * FROM tbl_envVar WHERE name LIKE 'admin-pass-hash' AND value LIKE '" . htmlspecialchars($passHash) . "'");
            // in case of wrong password
            if(mysqli_num_rows($result) != 1) {
                return false;
            }

            // close the database handle
            mysqli_close($handle);

            return true;
        }

        public static function changePassword($oldPass, $newPass)
        {

            // limit the maximum length of the new password
            // ...

            // check if the old password is correct if not abort
            $result = self::validateLogin("admin", $oldPass);
            if(!$result) {
                return false;
            }

            // Get a connection handle
            $handle = self::getDBConnection();

            // create SHA-256 hashes from the plain text passwords
            $oldPassHash = hash("sha256", $oldPass);
            $newPassHash = hash("sha256", $newPass);

            // when correct update the password hash in the database to the new one
            $updateQuery = "UPDATE tbl_envVar SET value = '" . htmlspecialchars($newPassHash) . "' WHERE name LIKE 'admin-pass-hash'";
            $result = mysqli_query($handle, $updateQuery);

            // close the database handle
            mysqli_close($handle);
            
            return true;
        }
    }
?>
