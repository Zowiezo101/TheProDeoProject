<?php

    namespace Shapes;

    class Title extends Module {
        private $title;
        private $sub;
        private $record;
        
        public function __construct($params = []) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "title":
                        $this->setTitle($value);
                        break;
                    case "sub":
                        $this->setSub($value);
                        break;
                    case "record":
                        $this->setRecord($value);
                        break;
                }
            }
        }

        public function setTitle($title) {
            $this->title = $title;
        }

        public function setSub($sub) {
            $this->sub = $sub;
        }

        public function setRecord($record) {
            $this->record = $record;
        }
        
        public function getContent() {
            global $dict;

            $title = '';
            if (isset($this->title) && $this->title !== '') {
                $title = $this->title;
                
                if (isset($this->record)) {
                    // If there is a record given, use the parameters as keys
                    // for the record instead of straight-up values.
                    $title = $this->record->{$this->title};
                }

                // If the title for some reason is a localization string, use this instead
                if (isset($dict[$title])) {
                    $title = $dict[$title];
                }
                
                $title = '<h1 class="mb-3">'.$title.'</h1>';
            }
            
            $sub = '';
            if (isset($this->sub) && $this->sub !== '') {      
                $sub = $this->sub;
                
                if (isset($this->record)) {
                    // If there is a record given, use the parameters as keys
                    // for the record instead of straight-up values.
                    $sub = $this->record->{$this->sub};
                }
                
                $sub = '<p class="lead">'.$sub.'</p>';
            }
            
            $content = '<div class="row">
                                    <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                                        '.$title.'
                                        '.$sub.'
                                    </div>
                                </div>';
            
            return $content;
        }
    }
