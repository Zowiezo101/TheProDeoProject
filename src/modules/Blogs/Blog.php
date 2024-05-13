<?php

    class Blog extends Module {
        private $colors = ["purple", "yellow", "red", "green", "blue"];
        private $color = "purple";
        private $title;
        private $id;
        private $text;
        private $user;
        private $date;
        
        public function __construct($record) {
            global $dict;
            
            // TODO: Do some checking for these values
            $this->title = $record->title;
            $this->id = "blog_{$record->id}";
            $this->text = $record->text;
                
            // If the user isn't defined for some reason, get it as anonymous
            $this->user = $record->name === "undefined" ? 
                        $dict["blogs.anonymous"] : 
                        $record->name;
                
            // The date of the blog, saved in UTC timezone
            $timezone = new DateTimeZone("UTC");

            // A datetime object
            $datetime = new DateTime($record->date, $timezone);

            // The date and time, formatted to a string
            $this->date = $datetime->format("j-n-Y H:i:s");
        }
        
        // Set the color of this blog. There are 5 colors and they are indicated
        // by integers 0-4
        public function setColor($color_idx) {
            if (true) {
                // TODO: Check this is a valid value
                $this->color = $this->colors[$color_idx];
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            global $dict;
            
            // The blog using data from the database
            $content = '
                <div class="row justify-content-center">
                    <div class="col-md-11 mb-3">
                        <h1 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--dark-'.$this->color.')">'.$this->title.'</h1>
                        <div class="text-center pb-2 pt-2 px-3 mb-0 h5" style="word-break:break-word; background-color: var(--light-'.$this->color.')" id="'.$this->id.'">'.$this->text.'</div>
                        <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-'.$this->color.')">'.$dict["blogs.posted_by"].' <a href="'.setParameters("settings").'" class="text-decoration-none text-body">'.$this->user.'</a> @ '.$this->date.' (UTC)</h6>
                    </div>
                </div>';
            
            return $content;
        }
    }
