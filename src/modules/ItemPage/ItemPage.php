<?php
    // The Parts used by this Page
    require "src/modules/ItemPage/Parts/Content/ItemContent.php";
    require "src/modules/ItemPage/Parts/Content/ItemDefault.php";
    require "src/modules/ItemPage/Parts/Content/ItemDetails.php";
    require "src/modules/ItemPage/Parts/Content/ItemTable.php";
    require "src/modules/ItemPage/Parts/Content/MapTable.php";
    require "src/modules/ItemPage/Parts/List/ItemList.php";    
    
    // The different items
    require "src/modules/ItemPage/Items/Item.php";
    require "src/modules/ItemPage/Items/ItemBook.php";
    require "src/modules/ItemPage/Items/ItemEvent.php";
    require "src/modules/ItemPage/Items/ItemPeople.php";
    require "src/modules/ItemPage/Items/ItemLocation.php";
    require "src/modules/ItemPage/Items/ItemSpecial.php";

    class ItemPage extends Module {
        private $item_list;
        protected $item_content;
        
        public function __construct($type) {
            global $TYPE_BOOK, $TYPE_EVENT, 
                    $TYPE_PEOPLE, $TYPE_LOCATION, 
                    $TYPE_SPECIAL;
            parent::__construct();
            
            switch($type) {
                case $TYPE_BOOK:
                    $item = new ItemBook();
                    break;
                case $TYPE_EVENT:
                    $item = new ItemEvent();
                    break;
                case $TYPE_PEOPLE:
                    $item = new ItemPeople();
                    break;
                case $TYPE_LOCATION:
                    $item = new ItemLocation();
                    break;
                case $TYPE_SPECIAL:
                    $item = new ItemSpecial();
                    break;
                default:
                    $item = null;
                    break;
            }
            
            if (isset($item)) {
                // There are a few presets we can use
                // If there isn't a valid type given, we need to add the content
                // ourselves manually (in case of maps)
                $item_list = $item->getItemList();
                $this->setList($item_list);

                $item_content = $item->getItemContent();
                $this->setContent($item_content);
            }
        }
        
        public function setList($item_list) {
            $this->item_list = $item_list;
        }
        
        public function setContent($item_content) {
            $this->item_content = $item_content;
        }
        
        // Return all the content of this module
        public function getContent() {
            $content = '<div class="row">
                    '.$this->item_list->getContent().'
                        
                    '.$this->item_content->getContent().'
                </div>';
            
            return $content;
        }
    }
