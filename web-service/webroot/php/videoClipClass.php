<?php

    /*
        This script defines a class that describes a video clip 
        and it's relevant attributes

        Future use
    */

    class VideoClip
    {
        private $filename = "";
        private $dateStr = "";
        private $timeStr = "";
        private $weekDay = "";
        private $fileSize = 0;

        public function __construct($path)
        {
            $this->filename = basename($path);
            // extract date and time from filename and store it
            $temp = explode(".", $path);
            $this->dateStr = str_replace("-", ".", $temp[1]);
            $this->timeStr = str_replace("-", ":", $temp[2]);

            // also determine the weekday of the date and the filesize
            $this->weekDay = VideoClip::getWeekdayStr(date("%w", strtotime($this->dateStr)));
            $this->fileSize = round(filesize($path) / 1000000);
        }

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
    }

?>