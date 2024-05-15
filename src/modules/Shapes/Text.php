<?php

    class Text extends Module {
        private $text;
        
        public function __construct($text = []) {
            // Parse the parameters given       
            $this->setText($text);
        }

        public function setText($text) {
            if (true) {
                // TODO: Check this is a valid value
                $this->text = $text;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            return $this->text;
        }
    }

