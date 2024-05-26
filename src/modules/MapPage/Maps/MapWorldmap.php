<?php

    class MapWorldmap extends Map {
        public function __construct() {
            global $TYPE_WORLDMAP,
                   $ONCLICK_SHOWONMAP;
            parent::__construct();
            
            /** These are the two main modules that are used for map pages */
            $this->MapList([
                "type" => $TYPE_WORLDMAP,
                "base_url" => "worldmap/map",
                "onclick" => $ONCLICK_SHOWONMAP
            ]);
            
            $map_content = $this->MapContent([
                "type" => $TYPE_WORLDMAP
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // No default content and no detail content, 
            // everything happens in Google Maps
            $map_content->addDefaultContent("");
        }
    }

