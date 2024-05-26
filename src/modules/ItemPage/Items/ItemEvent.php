<?php

    class ItemEvent extends Item {
        public function __construct() {
            global $TYPE_EVENT;
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $this->ItemList([
                "type" => $TYPE_EVENT,
                "base_url" => "events/event"
            ]);
            
            $item_content = $this->ItemContent([
                "type" => $TYPE_EVENT
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $item_content->addDetailContent($this->Title());

            // This table contains all the information about this item
            $item_content->addDetailContent($this->ItemTable());

            // This table contains maps related to this item
            $item_content->addDetailContent($this->MapTable());
        }
        
        private function Title() {
            return new Title([
                "title" => "name",
                "sub" => "descr"
            ]);
        }
        
        private function ItemTable() {
            global $dict,
                    $ROW_NOTES, $ROW_PEOPLES, $ROW_LOCATIONS, 
                    $ROW_SPECIALS, $ROW_EVENTS, $ROW_BOOKS;
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
                        "type" => $ROW_NOTES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.peoples"],
                        "data" => "peoples",
                        "type" => $ROW_PEOPLES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.locations"],
                        "data" => "locations",
                        "type" => $ROW_LOCATIONS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.specials"],
                        "data" => "specials",
                        "type" => $ROW_SPECIALS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.previous"],
                        "data" => "parents",
                        "type" => $ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.next"],
                        "data" => "children",
                        "type" => $ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.books"],
                        "type" => $ROW_BOOKS
                    ],
                ]
            ]);
        }
        
        private function MapTable() {
            global $dict, $TYPE_TIMELINE;
            return new MapTable([
                "title" => $dict["items.details.timeline"],
                "type" => $TYPE_TIMELINE
            ]);
        }
    }

