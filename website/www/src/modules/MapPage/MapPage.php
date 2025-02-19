<?php

    namespace MapPage;
    
    use ItemPage\ItemPage;
    
    // The different maps
    use Maps\MapTimeline;
    use Maps\MapFamilytree;
    use Maps\MapWorldmap;

    class MapPage extends ItemPage {
        protected $map_popup;
        
        public function __construct($type) {
            // Setting the parent Module
            parent::__construct($type);
            
            switch($type) {
                case TYPE_TIMELINE:
                    $map = new MapTimeline();
                    break;
                case TYPE_FAMILYTREE:
                    $map = new MapFamilytree();
                    break;
                case TYPE_WORLDMAP:
                    $map = new MapWorldmap();
                    break;
            }
            
            $map_list = $map->getMapList();
            $this->setList($map_list);

            $map_content = $map->getMapContent();
            $this->setContent($map_content);

            $map_popup = $map->getMapPopup();
            $this->setPopup($map_popup);
        }
        
        public function setPopup($map_popup) {
            $this->map_popup = $map_popup;
        }
        
        // Return all the content of this module
        public function getContent() {
            $content = '<div class="row">
                    '.$this->item_list->getContent().'
                    '.$this->item_content->getContent().'
                    '.$this->map_popup->getContent().'
                </div>';
            
            return $content;
        }
    }
