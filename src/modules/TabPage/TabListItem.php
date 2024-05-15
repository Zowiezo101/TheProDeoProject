<?php

    class TabListItem extends Module {
        private $id;
        private $title;
        private $icon;
        private $active;
        
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
                    case "title":
                        $this->setTitle($value);
                        break;
                    case "icon":
                        $this->setIcon($value);
                        break;
                    case "active":
                        $this->setActive($value);
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

        public function setTitle($title) {
            if (true) {
                // TODO: Check this is a valid value
                $this->title = $title;
            } else {
                // TODO: Throw an error
            }
        }

        public function setIcon($icon) {
            if (true) {
                // TODO: Check this is a valid value
                $this->icon = $icon;
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
        
        public function getContent() {
            
            $classes = "nav-link";
            // Set the tab as active tab when 'active' is true
            if (isset($this->active) && ($this->active === true)) {
                $classes = $classes." active";
            }
            
            // Create the TabListItem
            $content = '
            <li class="nav-item">
                <a href="" class="'. $classes.'" data-toggle="pill" data-target="#tab'.$this->id.'"> 
                    <i class="fa '.$this->icon.' text-muted fa-lg"></i>
                    '.strtoupper($this->title).'
                </a> 
            </li>';
            
            return $content;
        }
    }

