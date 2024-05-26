<?php

    class MapTimeline extends Map {
        public function __construct() {
            global $TYPE_TIMELINE,
                   $ONCLICK_LOADING;
            parent::__construct();
            
            /** These are the two main modules that are used for map pages */
            $this->MapList([
                "type" => $TYPE_TIMELINE,
                "base_url" => "timeline/map",
                "onclick" => $ONCLICK_LOADING
            ]);
            
            $map_content = $this->MapContent([
                "type" => $TYPE_TIMELINE
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $map_content->addDetailContent($this->Title());    
            
            // The SVG that is used to draw the map in
            $map_content->addDetailContent($this->Map());
            
            // The modal that shows up when selecting a sub map
            $map_content->addDetailContent($this->SubMap());
        }
        
        private function Title() {
            return new Title([
                "title" => "name",
            ]);
        }
        
        private function Map() {
            return new SVG();
        }
        
        private function SubMap() {
            return new Modal();
        }
    }

