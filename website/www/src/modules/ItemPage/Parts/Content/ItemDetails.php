<?php

    namespace Content;
    
    use Shapes\Module;

    class ItemDetails extends Module {
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
            $content = "";
            
            // Load the data of the selected item
            $id = filter_input(INPUT_GET, "id");
            $data = getItem($this->type, $id);
            
            if ($this->checkData($data) === false) {
                // Something went wrong
                $content = $this->getError();
            } else {
                // Get the first record, as there should be only one
                $record = $data->records[0];
                
                foreach ($this->content as $module) {
                    // Add this record to all the module for the ItemDetails 
                    // Module, but only if possible
                    if (method_exists($module, "setRecord")) {
                        $module->setRecord($record);
                    }
                }
                
                // Show all the content
                $content = parent::getContent();
            }
            
            return $content;
        }
    }
