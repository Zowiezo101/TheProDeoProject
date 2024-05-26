<?php

    class Map extends Module {
        private $map_list;
        private $map_content;
        
        public function MapList($params) {
            $this->map_list = new MapList($params);
            return $this->map_list;
        }
        
        public function MapContent($params) {
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

