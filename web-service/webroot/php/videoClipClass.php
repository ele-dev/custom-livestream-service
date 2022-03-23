<?php

    /*
        This script defines a class that describes a video clip 
        and it's relevant attributes
    */

    require_once "config.php";

    class VideoClip
    {
        // Static properties
        private static $clipList;

        // Attributes
        private $filename = "";
        private $dateStr = "";
        private $timeStr = "";
        private $weekDay = "";
        private $fileSize = 0;

        // Constructor
        public function __construct($path)
        {
            // Store the filename
            $this->filename = basename($path);

            // extract date and time from filename and store it
            $temp = explode(".", $path);
            $this->dateStr = str_replace("-", ".", $temp[1]);
            $this->timeStr = str_replace("-", ":", $temp[2]);

            // also determine the weekday of the date and the filesize
            $this->weekDay = VideoClip::getWeekdayStr(date("%w", strtotime($this->dateStr)));
            $this->fileSize = round(filesize($path) / 1000000);
        }

        // Modifier functions

        public function changeRecordDate($newDate) 
        {
            // Prevent unauthorized access
	        if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
		        return false;
	        }

            // Split current filename into segments and abort if segment count is invalid
            $temp = explode(".", $this->filename);
            if(count($temp) != 4) {
                return false;
            }

            // udpated the class member
            $this->dateStr = date("d.m.Y", strtotime($newDate));

            // construct new filenames based on the new record date
            $updatedMp4FileName = $temp[0] . "." . date("d-m-Y", strtotime($newDate)) . "." . $temp[2] . ".mp4";
            $updatedFlvFileName = $temp[0] . "." . date("d-m-Y", strtotime($newDate)) . "." . $temp[2] . ".flv";

            // attempt to rename files (mp4 and flv file)
            $result = rename(EnvGlobals::getVideoDir() . $this->filename, EnvGlobals::getVideoDir() . $updatedMp4FileName);
            if(!$result) {
                return false;
            } else {
                // Try the potential flv too
                rename(EnvGlobals::getVideoDir() . pathinfo($this->filename, PATHINFO_FILENAME) . ".flv", EnvGlobals::getVideoDir() . $updatedFlvFileName);
            }

            return true;
        }

        public function changeRecordTime($newTime) 
        {
            // Prevent unauthorized access
	        if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
                return false;
	        }

            // Split current filename into segments and abort if segment count is invalid
            $temp = explode(".", $this->filename);
            if(count($temp) != 4) {
                return false;
            }

            // udpated the class member
            $this->timeStr = $newTime;

            // construct new filenames based on the new record date
            $updatedMp4FileName = $temp[0] . "." . $temp[1] . "." . str_replace(":", "-", $newTime) . ".mp4";
            $updatedFlvFileName = $temp[0] . "." . $temp[1] . "." . str_replace(":", "-", $newTime) . ".flv";

            // attempt to rename files (mp4 and flv file)
            $result = rename(EnvGlobals::getVideoDir() . $this->filename, EnvGlobals::getVideoDir() . $updatedMp4FileName);
            if(!$result) {
                return false;
            } else {
                // Try the potential flv too
                rename(EnvGlobals::getVideoDir() . pathinfo($this->filename, PATHINFO_FILENAME) . ".flv", EnvGlobals::getVideoDir() . $updatedFlvFileName);
            }

            return true;
        }


        // Getters
        public function getFilename()
        {
            return $this->filename;
        }

        public function getRecordDate()
        {
            return $this->dateStr;
        }

        public function getRecordTime()
        {
            return $this->timeStr;
        }

        public function getWeekday()
        {
            return $this->weekDay;
        }

        public function getFilesize()
        {
            return $this->fileSize;
        }

        // Function to generate Weekday names from indexes
        public static function getWeekdayStr($dayIdx)
        {
            if($dayIdx == "%0")
                return "Sonntag";
            else if($dayIdx == "%1")
                return "Montag";
            else if($dayIdx == "%2")
                return "Dienstag";
            else if($dayIdx == "%3")
                return "Mittwoch";
            else if($dayIdx == "%4")
                return "Donnerstag";
            else if($dayIdx == "%5")
                return "Freitag";
            else 
                return "Samstag";
        }

        // Static functions
        public static function createClipList($clipDir)
        {
            $filePaths = glob($clipDir);
            for($i = 0; $i < count($filePaths); $i++)
            {
                self::$clipList[$i] = new VideoClip($filePaths[$i]);
            }
        }

        public static function printClipList($privileged)
        {
            for($i = 0; $i < count(self::$clipList); $i++)
            {
                // Get current clip
                $clip = self::$clipList[$i];

                // Print the formatted HTML table row
                if(($i+2) % 2 != 0) {
                    echo "<tr class='white'><td>" . $clip->getWeekday() . "</td><td>" 
                        . $clip->getRecordDate() . "</td><td>" . $clip->getRecordTime() . "</td><td>" 
                        . $clip->getFilesize() . " MB</td><td><a href='player.php?name=" . $clip->getFilename()
                        . "'><i class='fas fa-play-circle' style='color:black;font-size:23px;'></i></a></td>
                        <td><a href='media/" . $clip->getFilename() . "' download='video.mp4'>
                        <i class='fas fa-download' style='color:black;font-size:23px;'></i></a></td>";
                        // only visible to privileged people (admins)
                        if($privileged) {
                            // delete option
                            echo "<td><a href='" . htmlspecialchars("php/delete.php?file=" . $clip->getFilename()) 
                            . "'><i class='fas fa-trash-alt' style='color:black;font-size:23px;'></i></a></td>";

                            // modify option
                            echo "<td><a href='modify.php?file=" . $clip->getFilename() 
                            . "'><i class='fas fa-wrench' style='color:black;font-size:23px;'></i></a></td>";
                        }
                        echo "</tr>";
                } else {
                    echo "<tr class='grey'><td>" . $clip->getWeekday() . "</td><td>" 
                    . $clip->getRecordDate() . "</td><td>" . $clip->getRecordTime() . "</td><td>" 
                    . $clip->getFilesize() . " MB</td><td><a href='player.php?name=" . $clip->getFilename()
                    . "'><i class='fas fa-play-circle' style='color:black;font-size:23px;'></i></a></td>
                    <td><a href='media/" . $clip->getFilename() . "' download='video.mp4'>
                    <i class='fas fa-download' style='color:black;font-size:23px;'></i></a></td>";
                    // only visible to privileged people (admins)
                    if($privileged) {
                        // delete option
                        echo "<td><a href='" . htmlspecialchars("php/delete.php?file=" . $clip->getFilename()) 
                            . "'><i class='fas fa-trash-alt' style='color:black;font-size:23px;'></i></a></td>";

                        // modify option
                        echo "<td><a href='modify.php?file=" . $clip->getFilename() 
                        . "'><i class='fas fa-wrench' style='color:black;font-size:23px;'></i></a></td>";
                    }
                    echo "</tr>";
                }
            }
        }

        public static function sortClips()
        {
            // Sort the clips in the list by the date and time of recording (using bubble sort)
            do 
            {
                $done = true;
                for($i = 0; $i < count(self::$clipList) - 1; $i++)
                {
                    // create two timestamps
                    $timestamp1 = strtotime(self::$clipList[$i]->getRecordDate() . " " . self::$clipList[$i]->getRecordTime());
                    $timestamp2 = strtotime(self::$clipList[$i+1]->getRecordDate() . " " . self::$clipList[$i+1]->getRecordTime());
                    
                    // check if the order is correct
                    if($timestamp2 > $timestamp1) {
                        // swap the two entries
                        $t2 = self::$clipList[$i+1];
                        $t1 = self::$clipList[$i];
                        self::$clipList[$i] = $t2;
                        self::$clipList[$i+1] = $t1;

                        $done = false;
                    }
                }
            } while(!$done);
        }
    }

?>