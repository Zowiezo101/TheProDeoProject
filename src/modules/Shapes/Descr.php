<?php

    class Descr extends Module {
        private $title;
        private $sub;
        private $record;
        
        public function __construct($params = []) {
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
            $title = $this->title;
            $sub = $this->sub;
            
            if (isset($this->record)) {
                // If there is a record given, use the parameters as keys
                // for the record instead of straight-up values.
                $title = $this->record->$title;
                $sub = $this->record->$sub;
            }
            
            $content = '<div class="row">
                                    <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                                        <h1 class="mb-3">'.$title.'</h1>
                                        <p class="lead">'.$sub.'</p>
                                    </div>
                                </div>';
            
            return $content;
        }
    }
