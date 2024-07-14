<?php

    namespace Parts;
    
    use Shapes\Module;

    class TabListItem extends Module {
        private $id;
        private $title;
        private $icon;
        private $classes = "nav-link";
        
        public function __construct($params) {
            parent::__construct();
            
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

        private function setId($id) {
            // Take the ID as is
            $this->id = $id;
        }

        private function setTitle($title) {
            // Put the title in all uppercase
            $this->title = strtoupper($title);
        }

        private function setIcon($icon) {
            // The icon is a <i> tag with font-awesome icon
            $this->icon = '<i class="fa '.$icon.' text-muted fa-lg"></i>';
        }

        private function setActive($active) {
            // Set the tab as active tab when 'active' is true
            if ($active === true) {
                $this->classes = "nav-link active";
            }
        }
        
        public function getContent() {
            // Create the TabListItem
            $content = '
                            <li class="nav-item" id="'.$this->id.'_li" onclick="onTabClick()">
                                <a href="" class="'. $this->classes.'" data-toggle="pill" data-target="#'.$this->id.'"> 
                                    '.$this->icon.'
                                    '.$this->title.'
                                </a> 
                            </li>';
            
            return $content;
        }
    }
