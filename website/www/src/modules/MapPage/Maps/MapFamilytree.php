<?php

    namespace Maps;
    
    use Shapes\Title;
    use Content\SVG;

    class MapFamilytree extends Map {
        public function __construct() {
            parent::__construct();
            
            /** These are the two main modules that are used for map pages */
            $this->createMapList([
                "type" => TYPE_FAMILYTREE,
                "base_url" => "familytree/map",
                "onclick" => \List\ONCLICK_LOADING
            ]);
            
            $map_content = $this->createMapContent([
                "type" => TYPE_FAMILYTREE
            ]);

            $this->createMapPopup([
                "type" => TYPE_FAMILYTREE
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $map_content->addDetailContent($this->createTitle());    
            
            // The SVG that is used to draw the map in
            $map_content->addDetailContent($this->createMap());
        }
        
        private function createTitle() {
            return new Title([
                "title" => "name",
            ]);
        }
        
        private function createMap() {
            return new SVG();
        }
    }
