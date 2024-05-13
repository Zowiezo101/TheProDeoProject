<?php
    require "src/modules/Sidebar/PageList.php";
    require "src/modules/ItemPage/ItemContent.php";
    require "src/modules/Table/ItemTable.php";
    require "src/modules/Table/MapTable.php";

    class ItemPage extends Module {
        private $pagelist;
        private $item_content;

        public function __construct($params = []) {   
            // The Page List
            $this->pagelist = new PageList([
                "hide" => false,
            ]);
            
            // The Item Content
            $this->item_content = new ItemContent([
                "hide" => false
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
                $this->item_content->setType($type);
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
                $this->item_content->setId($id);
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
        
        // Add a module to the list of content for the ItemDefault Module
        // We're doing it from here to make it look nice
        public function addDefaultContent($module) {
            $this->item_content->addDefaultContent($module);
        }
        
        // Add a module to the list of content for the ItemDetails Module
        // We're doing it from here to make it look nice
        public function addDetailContent($module) {
            $this->item_content->addDetailContent($module);
        }
        
        // Return all the content of this module
        public function getContent() {
            $content = '<div class="row">
                    '.$this->pagelist->getContent().'
                    
                    '.$this->item_content->getContent().'
                </div>';
            
            return $content;
        }
    }
