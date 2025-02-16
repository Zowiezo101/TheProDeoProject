<?php

    namespace Items;
    
    use Shapes\Title;
    use List\ItemModal;
    use Content\ItemTable;
    use Content\MapTable;

    class ItemEvent extends Item {
        public function __construct() {
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $this->createItemList([
                "type" => TYPE_EVENT,
                "base_url" => "events/event",
                "columns" => [
                    "order_id"
                ]
            ]);
            
            $item_content = $this->createItemContent([
                "type" => TYPE_EVENT
            ]);
            
            $this->createItemModal([
                "filters" => [
                    [
                        "name" => "name",
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
                        "name" => "length",
                        "type" => ItemModal::INPUT_TEXT
                    ],
                    [
                        "name" => "date",
                        "type" => ItemModal::INPUT_TEXT
                    ],
                ]
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $item_content->addDetailContent($this->createTitle());

            // This table contains all the information about this item
            $item_content->addDetailContent($this->createItemTable());

            // This table contains maps related to this item
            $item_content->addDetailContent($this->createMapTable());
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
                        "title" => $dict["items.length"],
                        "data" => "length",
                    ],
                    [
                        "title" => $dict["items.date"],
                        "data" => "date",
                    ],
                    [
                        "title" => $dict["items.notes"],
                        "data" => "notes",
                        "type" => \Content\ROW_NOTES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.peoples"],
                        "data" => "peoples",
                        "type" => \Content\ROW_PEOPLES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.locations"],
                        "data" => "locations",
                        "type" => \Content\ROW_LOCATIONS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.specials"],
                        "data" => "specials",
                        "type" => \Content\ROW_SPECIALS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.previous"],
                        "data" => "parents",
                        "type" => \Content\ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.next"],
                        "data" => "children",
                        "type" => \Content\ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.books"],
                        "type" => \Content\ROW_BOOKS
                    ],
                ]
            ]);
        }
        
        private function createMapTable() {
            global $dict;
            return new MapTable([
                "title" => $dict["items.details.timeline"],
                "type" => TYPE_TIMELINE
            ]);
        }
    }
