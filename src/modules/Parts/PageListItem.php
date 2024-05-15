<?php

    class PageListItem extends Module {
        
        private $base_url;
        private $data;
        private $active;
        
        public function __construct($params=[]) {
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
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
            if (true) {
                // TODO: Check this is a valid value
                $this->data = $data;
            } else {
                // TODO: Throw an error
            }
        }

        public function setBaseUrl($url) {
            if (true) {
                // TODO: Check this is a valid value
                $this->base_url = $url;
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
            // The PageListItem, this is an item in the PageList.
            // The PageList is a sidebar used for the item and map pages
            // and contains a list of clickable items to view other items or maps
            $record = $this->data;
            if (isset($record)) {
                // The link to refer to
                $href = setParameters("{$this->base_url}/{$record->id}");

                // If an option in the sidebar is selected, it needs to be highlighted
                $classes = "list-group-item list-group-item-action";
                if ($this->active === true) {
                    $classes = $classes." active";
                }

                // The name to be shown in the sidebar
                $value = $record->name;
                if (isset($record->aka) && $record->aka != "") {
                    // The AKA value is only given when searching for a name and there is a hit
                    // with an AKA value.
                    $value = $value." ({$record->aka})";
                }

                $content = '
                                    <a href="'.$href.'" class="'.$classes.'">'.$value.'</a>';
            } else {
                // If no record is given, there are not enough items to fill the PageList with
                // Add some empty PageListItems to get a full page of items
                $content = '
                                    <a class="list-group-item list-group-item-action invisible"> empty </a>';
            }
            return $content;
        }
    }
