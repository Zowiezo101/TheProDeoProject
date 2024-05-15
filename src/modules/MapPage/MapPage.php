<?php
    require_once "src/modules/Sidebar/PageList.php";
    require "src/modules/MapPage/MapContent.php";

    class MapPage extends Module {
        private $pagelist;
        private $map_content;

        public function __construct($params = []) {   
            // The Page List
            $this->pagelist = new PageList([
                "true" => false,
            ]);
            
            // The Item Content
            $this->map_content = new MapContent([
                "true" => false
            ]);
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
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

        public function setType($type) {
            if (true) {
                // Pass these parameters to the PageList and ItemContent
                $this->pagelist->setType($type);
                $this->map_content->setType($type);
            } else {
                // TODO: Throw an error
            }
        }

        public function setBaseUrl($url) {
            if (true) {
                // TODO: Check this is a valid value
                // Pass these parameters to the PageList
                $this->pagelist->setBaseUrl($url);
            } else {
                // TODO: Throw an error
            }
        }

        public function setId($id) {
            if (true) {
                // TODO: Check this is a valid value
                // Pass these parameters to the PageList and ItemContent
                $this->pagelist->setId($id);
                $this->map_content->setId($id);
            } else {
                // TODO: Throw an error
            }
        }
        
        // Add a module to the list of content for the ItemDefault Module
        // We're doing it from here to make it look nice
        public function addDefaultContent($module) {
            $this->map_content->addDefaultContent($module);
        }
        
        // Add a module to the list of content for the ItemDetails Module
        // We're doing it from here to make it look nice
        public function addDetailContent($module) {
            $this->map_content->addDetailContent($module);
        }
        
        // Return all the content of this module
        public function getContent() {
            $content = '<div class="row">
                    '.$this->pagelist->getContent().'
                    
                    '.$this->map_content->getContent().'
                </div>';
            
            return $content;
        }
    }

