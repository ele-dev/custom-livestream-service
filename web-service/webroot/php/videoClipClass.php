<?php

    /*
        This script defines a class that describes a video clip 
        and it's relevant attributes

        Future use
    */

    class VideoClip
    {
        private $filename = "";

        public function __construct($filename)
        {
            $this->filename = $filename;
        }

        public function getFilename()
        {
            return $this->filename;
        }
    }

?>