<?php

    namespace Maps;
    
    use Shapes\Module;
    use List\MapList;
    use Content\MapContent;

    class Map extends Module {
        private $map_list;
        private $map_content;
        
        public function createMapList($params) {
            $this->map_list = new MapList($params);
            return $this->map_list;
        }
        
        public function createMapContent($params) {
            $this->map_content = new MapContent($params);
            return $this->map_content;
        }
        
        public function getMapList() {
            return $this->map_list;
        }
        
        public function getMapContent() {
            return $this->map_content;
        }
    }
