<?php

    class ItemBook extends Item {
        public function __construct() {
            global $TYPE_BOOK;
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $this->ItemList([
                "type" => $TYPE_BOOK,
                "base_url" => "books/book"
            ]);
            
            $item_content = $this->ItemContent([
                "type" => $TYPE_BOOK
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $item_content->addDetailContent($this->Title());

            // This table contains all the information about this item
            $item_content->addDetailContent($this->ItemTable());
        }
        
        private function Title() {
            return new Title([
                "title" => "name",
                "sub" => "summary"
            ]);
        }
        
        private function ItemTable() {
            global $dict, $ROW_NOTES;
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
                        "type" => $ROW_NOTES,
                        "hide-empty" => true
                    ]
                ]
            ]);
        }
    }

