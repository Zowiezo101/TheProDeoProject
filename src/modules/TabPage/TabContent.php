<?php

    class TabContent extends Module{
        private $id;
        private $active;
        private $extra_classes;
        private $tab_content = "";
        
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
                    case "active":
                        $this->setActive($value);
                        break;
                    case "extra-classes":
                        $this->setExtraClasses($value);
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

        public function setActive($active) {
            if (true) {
                // TODO: Check this is a valid value
                $this->active = $active;
            } else {
                // TODO: Throw an error
            }
        }

        public function setExtraClasses($classes) {
            if (true) {
                // TODO: Check this is a valid value
                $this->extra_classes = $classes;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function addContent($content) {
            $this->tab_content = $this->tab_content.$content;
        }   
        
        public function setContent($content) {
            $this->tab_content = $content;
        }
        
        public function getContent() {
            
            $classes = "tab-pane fade";
            // Set the tab as active tab when 'active' is true
            if (isset($this->active) && ($this->active === true)) {
                $classes = $classes." show active";
            }
            // Add these classes as well
            if (isset($this->extra_classes)) {
                $classes = $classes." ".$this->extra_classes;
            }
            
            $content = '
                    <div class="'.$classes.'" id="tab'.$this->id.'" role="tabpanel">
                        '.$this->tab_content.'
                    </div>';
            
            return $content;
        }
    }

