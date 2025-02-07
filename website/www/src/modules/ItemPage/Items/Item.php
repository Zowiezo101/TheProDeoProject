<?php

    namespace Items;
    
    use Shapes\Module;
    use List\ItemList;
    use List\ItemModal;
    use Content\ItemContent;

    class Item extends Module {
        private $item_list;
        private $item_content;
        private $item_modal;
        
        public function createItemList($params) {
            $this->item_list = new ItemList($params);
            return $this->item_list;
        }
        
        public function createItemContent($params) {
            $this->item_content = new ItemContent($params);
            return $this->item_content;
        }
        
        public function createItemModal($params) {
            $this->item_modal = new ItemModal($params);
            return $this->item_modal;
        }
        
        public function getItemList() {
            return $this->item_list;
        }
        
        public function getItemContent() {
            return $this->item_content;
        }
        
        public function getItemModal() {
            return $this->item_modal;
        }
    }
