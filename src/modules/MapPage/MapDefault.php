<?php
    
    class MapDefault extends Module {
        
        private $type;
        
        public function __construct($params = []) {   
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
            if (true) {
                // TODO: Check this is a valid value
                $this->type = $type;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            global $dict;
            
            if ($this->content === []) {
                // If no content is given, set the default value
                $descr = new Descr([
                    "title" => $dict["navigation.{$this->type}"],
                    "sub" => $dict["{$this->type}.overview"]
                ]);
                $this->addContent($descr);
            }
            
            // Show all the content
            return parent::getContent();
        }
    }