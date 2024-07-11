<?php

    namespace Items;
    
    use Shapes\Title;
    use Content\ItemTable;

    class ItemSpecial extends Item {
        public function __construct() {
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $this->createItemList([
                "type" => TYPE_SPECIAL,
                "base_url" => "specials/special"
            ]);
            
            $item_content = $this->createItemContent([
                "type" => TYPE_SPECIAL
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
                "sub" => "descr"
            ]);
        }
        
        private function createItemTable() {
            global $dict;
            return new ItemTable([
                "title" => $dict["items.details"],
                "rows" => [
                    [
                        "title" => $dict["items.meaning_name"],
                        "data" => "meaning_name",
                    ],
                    [
                        "title" => $dict["items.type"],
                        "data" => "type",
                        "type" => \Content\ROW_TYPE
                    ],
                    [
                        "title" => $dict["items.notes"],
                        "data" => "notes",
                        "type" => \Content\ROW_NOTES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.events"],
                        "data" => "events",
                        "type" => \Content\ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.book_start"],
                        "type" => \Content\ROW_BOOK_START
                    ],
                    [
                        "title" => $dict["items.book_end"],
                        "type" => \Content\ROW_BOOK_END
                    ],
                ]
            ]);
        }
    }
