<?php
    /*
        This file holds all the environment variables which are 
        relevant for software deploy.
        all global configs should be set here and retrieved from here
        using static functions
    */

    class EnvGlobals
    {
        private static $hls_http_stream = "unset";
        private static $media_http_url = "unset";
        private static $news_text_line = "unset";

        private static $video_dir = "/mnt/recordings/";
        private static $upload_dir = "/mnt/uploads/";

	    // For production deploy (behind reverse proxy)
        // private static $hls_http_stream = "https://<subdomain>.<domain>:8443/hls/stream.m3u8";
        private static $vid_player_width = 1280;
        private static $vid_player_height = 720;
        private static $db_host = "dbHost";
        private static $db_user = "dbUser";
        private static $db_pass = "db-pass";
        private static $db_databaseName = "testDB";
        private static $viewerSessionLifetime = 20;         // after 20s viewer session outdated

        public static function getDBConnection() 
        {
            // return mysqli connection handle
            $dbCon = mysqli_connect(self::$db_host, self::$db_user, self::$db_pass, self::$db_databaseName);

            return $dbCon;
        }

        public static function isLive()
        {
            // Get a database connection handle
            $handle = self::getDBConnection();

            // If HLS URL isnt't set yet then fetch it from the database
            if(self::$hls_http_stream == "unset") {

                $result = mysqli_query($handle, "SELECT * FROM tbl_envVar WHERE name = 'hls-url'");
                $dataset = mysqli_fetch_assoc($result);
                self::$hls_http_stream = $dataset["value"];
            }

            // Detect stream status, update database and return status after closing db connection
            $result = fopen(self::$hls_http_stream, "rb");
            if($result == false) {
                $updateQuery = "UPDATE tbl_envVar SET value = 'offline' WHERE name LIKE 'stream-status'";
                $result = mysqli_query($handle, $updateQuery);
                mysqli_close($handle);
                return false;
            } else {
                $updateQuery = "UPDATE tbl_envVar SET value = 'online' WHERE name LIKE 'stream-status'";
                $result = mysqli_query($handle, $updateQuery);
                mysqli_close($handle);
                return true;
            }
        }

        public static function getStreamUrl()
        {
            // If HLS URL isnt't set yet then fetch it from the database
            if(self::$hls_http_stream == "unset") {

                $handle = self::getDBConnection();

                $result = mysqli_query($handle, "SELECT * FROM tbl_envVar WHERE name = 'hls-url'");
                $dataset = mysqli_fetch_assoc($result);
                self::$hls_http_stream = $dataset["value"];

                mysqli_close($handle);
            }

            return self::$hls_http_stream;
        }

        public static function getMediaUrl()
        {
            // If the media URL isn't set yet then construct it based on the HLS url
            if(self::$media_http_url == "unset") {
                $url = self::getStreamUrl();
                $temp = explode("/", $url);
                self::$media_http_url = $temp[0] . "/" . $temp[1] . "/" . $temp[2] . "/recordings/";
            }

            return self::$media_http_url;
        }

        public static function getVideoDir() 
        {
            return self::$video_dir;
        }

        public static function getUploadDir()
        {
            return self::$upload_dir;
        }

        public static function getNewsText()
        {
            // If not set yet then fetch it from the database
            if(self::$news_text_line == "unset") {
                $handle = self::getDBConnection();

                $result = mysqli_query($handle, "SELECT * FROM tbl_envVar WHERE name LIKE 'news-text'");
                $dataset = mysqli_fetch_assoc($result);
                self::$news_text_line = $dataset["value"];

                mysqli_close($handle);
            }

            return self::$news_text_line;
        }

        public static function getViewerSessionLifetime()
        {
            return self::$viewerSessionLifetime;
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

        public static function changeStreamUrl($newHlsUrl)
        {
            // Get a connection handle
            $handle = self::getDBConnection();

            // update the local cached stream url 
            self::$hls_http_stream = htmlspecialchars($newHlsUrl);

            // then store the new url in the database
            $updateQuery = "UPDATE tbl_envVar SET value = '" . htmlspecialchars($newHlsUrl) . "' WHERE name LIKE 'hls-url'";
            $result = mysqli_query($handle, $updateQuery);

            // close the database handle
            mysqli_close($handle);
            
            return true;
        }

        public static function changeNewsText($textLine)
        {
            // Get a connection handle
            $handle = self::getDBConnection();

            // update the local cached text
            self::$news_text_line = htmlspecialchars($textLine);

            // then store it in the database
            $updateQuery = "UPDATE tbl_envVar SET value = '" . htmlspecialchars($textLine) . "' WHERE name LIKE 'news-text'";
            $result = mysqli_query($handle, $updateQuery);

            // close the databse connection handle
            mysqli_close($handle);

            return true;
        }
    }
?>
