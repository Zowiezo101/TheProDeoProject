<?php
    require "src/modules/DataPage/DataContent.php";
    require "src/modules/DataPage/DataDefault.php";
    require "src/modules/DataPage/DataDetails.php";

    class DataPage extends Module {
        private $page_list;
        private $data_content;

        public function __construct($params = []) {   
            // The Page List
            $this->page_list = new PageList();
            
            // The Data Content
            $this->data_content = new DataContent();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "hide":
                        $this->setHide($value);
                        break;
                    case "type":
                        $this->setType($value);
                        break;
                    case "base_url":
                        $this->setBaseUrl($value);
                        break;
                    case "id":
                        $this->setId($value);
                        break;
                }
            }
        }

        public function setHide($hide) {
            if (true) {
                // Pass these parameters to the PageList and DataContent
                $this->page_list->setHide($hide);
                $this->data_content->setHide($hide);
            } else {
                // TODO: Throw an error
            }
        }

        public function setType($type) {
            if (true) {
                // Pass these parameters to the PageList and DataContent
                $this->page_list->setType($type);
                $this->data_content->setType($type);
            } else {
                // TODO: Throw an error
            }
        }

        public function setBaseUrl($url) {
            if (true) {
                // TODO: Check this is a valid value
                // Pass these parameters to the PageList
                $this->page_list->setBaseUrl($url);
            } else {
                // TODO: Throw an error
            }
        }

        public function setId($id) {
            if (true) {
                // TODO: Check this is a valid value
                // Pass these parameters to the PageList and DataContent
                $this->page_list->setId($id);
                $this->data_content->setId($id);
            } else {
                // TODO: Throw an error
            }
        }
        
        // Return a new Description Module
        public function Descr($params) {
            return new Descr($params);
        }
        
        // Return a new Table Module
        public function ItemTable($params) {
            return new ItemTable($params);
        }
        
        // Return a new Table Module
        public function MapTable($params) {
            return new MapTable($params);
        }
        
        // Add a module to the list of content for the DataDefault Module
        // We're doing it from here to make it look nice
        public function addDefaultContent($module) {
            $this->data_content->addDefaultContent($module);
        }
        
        // Add a module to the list of content for the DataDetails Module
        // We're doing it from here to make it look nice
        public function addDetailContent($module) {
            $this->data_content->addDetailContent($module);
        }
        
        // Return all the content of this module
        public function getContent() {
            $content = '<div class="row">
                    '.$this->page_list->getContent().'
                    
                    '.$this->data_content->getContent().'
                </div>';
            
            return $content;
        }
    }
