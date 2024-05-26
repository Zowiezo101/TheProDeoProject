<?php

    class LoadingScreen extends Module {
        
        private $type;
        
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
            
            // The selected ID
            $id = filter_input(INPUT_GET, "id");
            $classes = 'col-12 w-75 text-center';
            if (!isset($id)) {
                $classes .= ' d-none';
            }

            // A simple module used to create a loading screen
            $content = '
                                <div id="loading_screen" class="'.$classes.'" style="
                                                position: absolute;
                                                top: 50%;
                                                left: 50%;
                                                transform: translate(-50%, -50%);
                                                -webkit-transform: translate(-50%, -50%) ">
                                    <p>'.$dict["loading.{$this->type}"].'</p>
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">'.$dict["loading"].'</span>
                                    </div>
                                </div>';
            
            return $content;
        }
    }

