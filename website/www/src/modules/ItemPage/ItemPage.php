<?php

    namespace ItemPage;
    
    use Shapes\Module;
    
    // The different items
    use Items\ItemBook;
    use Items\ItemEvent;
    use Items\ItemPeople;
    use Items\ItemLocation;
    use Items\ItemSpecial;
    

    class ItemPage extends Module {
        protected $item_list;
        protected $item_content;
        protected $item_modal;
        
        public function __construct($type) {
            parent::__construct();
            
            switch($type) {
                case TYPE_BOOK:
                    $item = new ItemBook();
                    break;
                case TYPE_EVENT:
                    $item = new ItemEvent();
                    break;
                case TYPE_PEOPLE:
                    $item = new ItemPeople();
                    break;
                case TYPE_LOCATION:
                    $item = new ItemLocation();
                    break;
                case TYPE_SPECIAL:
                    $item = new ItemSpecial();
                    break;
                default:
                    $item = null;
                    break;
            }
            
            if (isset($item)) {
                // There are a few presets we can use
                $item_list = $item->getItemList();
                $this->setList($item_list);

                $item_content = $item->getItemContent();
                $this->setContent($item_content);

                $item_modal = $item->getItemModal();
                $this->setModal($item_modal);
            }
        }
        
        public function setList($item_list) {
            $this->item_list = $item_list;
        }
        
        public function setContent($item_content) {
            $this->item_content = $item_content;
        }
        
        public function setModal($item_modal) {
            $this->item_modal = $item_modal;
        }
        
        // Return all the content of this module
        public function getContent() {
            $content = '<div class="row">
                    '.$this->item_list->getContent().'
                    '.$this->item_content->getContent().'
                    '.$this->item_modal->getContent().'
                </div>';
            
            return $content;
        }
    }
