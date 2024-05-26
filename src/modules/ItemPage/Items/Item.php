<?php

    class Item extends Module {
        private $item_list;
        private $item_content;
        
        public function ItemList($params) {
            $this->item_list = new ItemList($params);
            return $this->item_list;
        }
        
        public function ItemContent($params) {
            $this->item_content = new ItemContent($params);
            return $this->item_content;
        }
        
        public function getItemList() {
            return $this->item_list;
        }
        
        public function getItemContent() {
            return $this->item_content;
        }
        
        public function getItemTable() {
            return $this->item_content;
        }
    }

