<?php

    namespace Shapes;

    class Text extends Module {
        private $text;
        
        public function __construct($text = []) {
            parent::__construct();
            
            // Parse the parameters given       
            $this->setText($text);
        }

        public function setText($text) {
            $this->text = $text;
        }
        
        public function getContent() {
            return $this->text;
        }
    }
