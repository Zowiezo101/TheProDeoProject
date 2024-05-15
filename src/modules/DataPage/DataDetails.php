<?php

    class DataDetails extends Module {
        private $id;
        private $type;
        
        public function __construct($params = []) {     
            // Parse the parameters given       
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "id":
                        $this->setId($value);
                        break;
                    case "type":
                        $this->setType($value);
                        break;
                }
            }
        }

        public function setId($id) {
            if (true) {
                // TODO: Check this is a valid value
                $this->id = $id;
            } else {
                // TODO: Throw an error
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
            $content = "";
            
            // Load the data for the selected item
            $data = getItem($this->type, $this->id);
            
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