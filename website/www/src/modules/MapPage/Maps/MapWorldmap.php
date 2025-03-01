<?php

    namespace Maps;

    class MapWorldmap extends Map {
        public function __construct() {
            parent::__construct();
            
            /** These are the two main modules that are used for map pages */
            $this->createMapList([
                "type" => TYPE_WORLDMAP,
                "base_url" => "worldmap/map",
                "onclick" => \List\ONCLICK_SHOWONMAP
            ]);
            
            $map_content = $this->createMapContent([
                "type" => TYPE_WORLDMAP
            ]);

            $this->createMapPopup([
                "type" => TYPE_WORLDMAP
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // No default content and no detail content, 
            // everything happens in Google Maps
            $map_content->addDefaultContent("");
        }
    }
