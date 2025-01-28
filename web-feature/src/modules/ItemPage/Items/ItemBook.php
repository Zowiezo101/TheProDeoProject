<?php

    namespace Items;
    
    use Shapes\Title;
    use List\ItemModal;
    use Content\ItemTable;

    class ItemBook extends Item {
        public function __construct() {
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $this->createItemList([
                "type" => TYPE_BOOK,
                "base_url" => "books/book"
            ]);
            
            $item_content = $this->createItemContent([
                "type" => TYPE_BOOK
            ]);
            
            $this->createItemModal([
                "filters" => [
                    [
                        "name" => "name",
                        "type" => ItemModal::INPUT_TEXT
                    ],
                    [
                        "name" => "num_chapters",
                        "type" => ItemModal::INPUT_SLIDER
                    ],
                ]
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $item_content->addDetailContent($this->createTitle());

            // This table contains all the information about this item
            $item_content->addDetailContent($this->createItemTable());
        }
        
        private function createTitle() {
            return new Title([
                "title" => "name",
                "sub" => "summary"
            ]);
        }
        
        private function createItemTable() {
            global $dict;
            return new ItemTable([
                "title" => $dict["items.details"],
                "rows" => [
                    [
                        "title" => $dict["items.num_chapters"],
                        "data" => "num_chapters",
                    ],
                    [
                        "title" => $dict["items.notes"],
                        "data" => "notes",
                        "type" => \Content\ROW_NOTES,
                        "hide-empty" => true
                    ]
                ]
            ]);
        }
    }
