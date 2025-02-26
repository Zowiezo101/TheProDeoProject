<?php

    namespace Items;
    
    use Shapes\Title;
    use List\ItemModal;
    use Content\ItemTable;

    class ItemLocation extends Item {
        public function __construct() {
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $item_list = $this->createItemList([
                "type" => TYPE_LOCATION,
                "base_url" => "locations/location"
            ]);
            
            $item_content = $this->createItemContent([
                "type" => TYPE_LOCATION
            ]);
            
            $this->createItemModal([
                "options" => $item_list->getOptions(),
                "filters" => [
                    [
                        "name" => "name",
                        "type" => ItemModal::INPUT_TEXT
                    ],
                    [
                        "name" => "meaning_name",
                        "type" => ItemModal::INPUT_TEXT
                    ],
                    [
                        "name" => "descr",
                        "type" => ItemModal::INPUT_TEXT
                    ],
                    [
                        "name" => "start",
                        "type" => ItemModal::INPUT_BOOK
                    ],
                    [
                        "name" => "end",
                        "type" => ItemModal::INPUT_BOOK
                    ],
                    [
                        "name" => "type",
                        "type" => ItemModal::INPUT_SELECT
                    ]
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
                        "title" => $dict["items.aka"],
                        "type" => \Content\ROW_AKA
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
                        "title" => $dict["items.coordinates"],
                        "type" => \Content\ROW_COORDS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.events"],
                        "data" => "events",
                        "type" => \Content\ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.peoples"],
                        "data" => "peoples",
                        "type" => \Content\ROW_PEOPLES,
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
