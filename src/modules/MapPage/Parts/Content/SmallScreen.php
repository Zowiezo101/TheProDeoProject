<?php

    class SmallScreen extends Module {
        
        private $type = false;
        
        public function __construct($params = []) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "type":
                        $this->setType($value);
                        break;
                }
            }
        }

        public function setType($type) {
            $this->type = $type;
        }
        
        public function getContent() {
            global $dict;
            
            // This screen should only be visible if small screens are hidden
            // This means that whenever a window is too small for content,
            // it will show this content instead
            $content = '
                        <div id="small_screen" class="d-md-none">
                            <div class="row text-center justify-content-center">
                                <div class="col-lg-11 px-lg-5 px-md-3">
                                    <h1 class="mb-3">'.$dict["navigation.{$this->type}"].'</h1>
                                    <p class="lead">'.$dict["small_screen.descr"].'</p>
                                </div>
                            </div>
                        </div>';
            
            return $content;
        }
    }

