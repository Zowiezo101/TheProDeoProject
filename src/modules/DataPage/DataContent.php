<?php

    class DataContent extends Module {
        private $id;
        
        private $default;
        private $details;
        private $toggle_menu;
        
        public function __construct($params = []) {
            // The content when no ID is given
            $this->default = new DataDefault();
            
            // The content when an ID is given
            $this->details = new DataDetails();
            
            // A toggle button to hide the PageList
            $this->toggle_menu = new ToggleMenu();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "id":
                        $this->setId($value);
                        break;
                    case "hide":
                        $this->setHide($value);
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
                $this->details->setId($id);
            } else {
                // TODO: Throw an error
            }
        }

        public function setHide($hide) {
            if (true) {
                // TODO: Check this is a valid value
                // Pass these parameters to the DataDefault and DataDetails
                $this->default->setHide($hide);
                $this->details->setHide($hide);
            } else {
                // TODO: Throw an error
            }
        }

        public function setType($type) {
            if (true) {
                // TODO: Check this is a valid value
                // Pass these parameters to the DataDefault and DataDetails
                $this->default->setType($type);
                $this->details->setType($type);
            } else {
                // TODO: Throw an error
            }
        }
        
        // Add a module to the list of content for the DataDefault Module
        // We're doing it from here to make it look nice
        public function addDefaultContent($module) {
            $this->default->addContent($module);
        }
        
        // Add a module to the list of content for the DataDetails Module
        // We're doing it from here to make it look nice
        public function addDetailContent($module) {
            $this->details->addContent($module);
        }
        
        public function getContent() {
            $content = '';
            
            if ($this->id !== null) {
                // Show the details of this item
                $content = $this->details->getContent();
            } else {
                // Show the default content when no item is selected
                $content = $this->default->getContent();
            }
            
            // Wrap it all into some divs
            return '<!-- The column with the selected content -->
                    <div id="content_col" class="col py-5">
                        <div id="content_row" class="row h-100">
                            <div id="item_content" class="col-12 h-100">
                                '.$content.'
                            </div>
                            '.$this->toggle_menu->getContent().'
                        </div>
                    </div>';
        }
    }
