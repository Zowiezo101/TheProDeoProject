<?php
    // The Parts used by this Page
    require "src/modules/MapPage/Parts/Content/MapContent.php";
    require "src/modules/MapPage/Parts/Content/LoadingScreen.php";
    require "src/modules/MapPage/Parts/Content/SmallScreen.php";
    require "src/modules/MapPage/Parts/Content/MapDetails.php";
    require "src/modules/MapPage/Parts/Content/Modal.php";
    require "src/modules/MapPage/Parts/Content/SVG.php";
    require "src/modules/MapPage/Parts/List/MapList.php";
    
    // The different maps
    require "src/modules/MapPage/Maps/Map.php";
    require "src/modules/MapPage/Maps/MapTimeline.php";
    require "src/modules/MapPage/Maps/MapFamilytree.php";
    require "src/modules/MapPage/Maps/MapWorldmap.php";

    class MapPage extends ItemPage {
        
        public function __construct($type) {
            global $TYPE_TIMELINE, $TYPE_FAMILYTREE, 
                    $TYPE_WORLDMAP;
            // Setting the parent Module
            parent::__construct($type);
            
            switch($type) {
                case $TYPE_TIMELINE:
                    $map = new MapTimeline();
                    break;
                case $TYPE_FAMILYTREE:
                    $map = new MapFamilytree();
                    break;
                case $TYPE_WORLDMAP:
                    $map = new MapWorldmap();
                    break;
            }
            
            $map_list = $map->getMapList();
            $this->setList($map_list);

            $map_content = $map->getMapContent();
            $this->setContent($map_content);
        }
    }
