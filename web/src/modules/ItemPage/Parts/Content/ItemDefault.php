<?php

    namespace Content;
    
    use Shapes\Module;
    use Shapes\Title;
    
    class ItemDefault extends Module {
        
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
            
            if ($this->content === []) {
                // If no content is given, set the default value
                $title = new Title([
                    "title" => $dict["navigation.{$this->type}"],
                    "sub" => $dict["{$this->type}.overview"]
                ]);
                $this->addContent($title);
            }
            
            // Show all the content
            return parent::getContent();
        }
    }
