<?php

    class MapFamilytree extends Map {
        public function __construct() {
            global $TYPE_FAMILYTREE,
                   $ONCLICK_LOADING;
            parent::__construct();
            
            /** These are the two main modules that are used for map pages */
            $this->MapList([
                "type" => $TYPE_FAMILYTREE,
                "base_url" => "familytree/map",
                "onclick" => $ONCLICK_LOADING
            ]);
            
            $map_content = $this->MapContent([
                "type" => $TYPE_FAMILYTREE
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $map_content->addDetailContent($this->Title());    
            
            // The SVG that is used to draw the map in
            $map_content->addDetailContent($this->Map());
        }
        
        private function Title() {
            return new Title([
                "title" => "name",
            ]);
        }
        
        private function Map() {
            return new SVG();
        }
    }

