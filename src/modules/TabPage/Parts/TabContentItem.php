<?php

    class TabContentItem extends Module {
        private $id;
        private $classes = "tab-pane fade";
        
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

        private function setId($id) {
            // Take the ID as is
            $this->id = $id;
        }

        private function setActive($active) {
            // Set the tab as active tab when 'active' is true
            if ($active === true) {
                $this->classes = $this->classes." show active";
            }
        }

        private function setExtraClasses($classes) {
            // Add extra classes
            $this->classes = $this->classes." ".$classes;
        }
        
        public function getContent() {
            // Create the TabContentItem
            $content = '
                            <div class="'.$this->classes.'" id="'.$this->id.'" role="tabpanel">
                                '.parent::getContent().'
                            </div>';
            
            return $content;
        }
    }

