<?php

    namespace List;
    
    use Shapes\Module;

    class ItemListItem extends Module {        
        // Properties for this Module
        protected $data;
        protected $classes = "list-group-item list-group-item-action";
        protected $href;
        protected $value;
        
        public function __construct($params=[]) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        public function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "data":
                        $this->setData($value);
                        break;
                    case "base_url":
                        $this->setBaseUrl($value);
                        break;
                    case "active":
                        $this->setActive($value);
                        break;
                }
            }
        }

        public function setData($data) {
            $this->data = $data;
        }

        public function setBaseUrl($url) {
            $this->href = setParameters($url);
        }

        public function setActive($active) {
            if ($active === true) {
                $this->classes .= " active";
            }
        }

        public function setBehavior($behavior) {
            if (true) {
                // TODO: Check this is a valid value
                $this->behavior = $behavior;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {            
            // The PageListItem, this is an item in the PageList.
            // The PageList is a sidebar used for the item and map pages
            // and contains a list of clickable items to view other items or maps
            $record = $this->data;
            if (isset($record)) {
                // The link to refer to
                $href = "$this->href/{$record->id}";
                
                // The name to be shown in the sidebar
                $value = $record->name;
                if (isset($record->aka) && $record->aka != "") {
                    // The AKA value is only given when searching for a name and there is a hit
                    // with an AKA value.
                    $value = $value." ({$record->aka})";
                }

                $content = '
                                    <a href="'.$href.'" class="'.$this->classes.'">'.$value.'</a>';
            } else {
                // If no record is given, there are not enough items to fill the PageList with
                // Add some empty PageListItems to get a full page of items
                $content = '
                                    <a class="list-group-item list-group-item-action invisible"> empty </a>';
            }
            return $content;
        }
    }
