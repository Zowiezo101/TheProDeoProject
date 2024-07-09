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
            if (true) {
                // TODO: Check this is a valid value
                $this->title = $title;
            } else {
                // TODO: Throw an error
            }
        }

        public function setSub($sub) {
            if (true) {
                // TODO: Check this is a valid value
                $this->sub = $sub;
            } else {
                // TODO: Throw an error
            }
        }

        public function setRecord($record) {
            if (true) {
                // TODO: Check this is a valid value
                $this->record = $record;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            $title = '';
            if (isset($this->title) && $this->title !== '') {
                $title = $this->title;
                
                if (isset($this->record)) {
                    // If there is a record given, use the parameters as keys
                    // for the record instead of straight-up values.
                    $title = $this->record->{$this->title};
                }
                // TODO: Global Timeline still shows up as global.timeline (also in the timeline itself)
                
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
