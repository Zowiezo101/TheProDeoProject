<?php

    class ItemPeople extends Item {
        public function __construct() {
            global $TYPE_PEOPLE;
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $this->ItemList([
                "type" => $TYPE_PEOPLE,
                "base_url" => "peoples/people"
            ]);
            
            $item_content = $this->ItemContent([
                "type" => $TYPE_PEOPLE
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
            global $dict, $ROW_AKA, $ROW_NOTES, 
                    $ROW_PEOPLES, $ROW_LOCATIONS, $ROW_EVENTS, 
                    $ROW_TYPE, $ROW_BOOK_START, $ROW_BOOK_END;
            return new ItemTable([
                "title" => $dict["items.details"],
                "rows" => [
                    [
                        "title" => $dict["items.meaning_name"],
                        "data" => "meaning_name",
                    ],
                    [
                        "title" => $dict["items.aka"],
                        "type" => $ROW_AKA
                    ],
                    [
                        "title" => $dict["items.father_age"],
                        "data" => "father_age",
                    ],
                    [
                        "title" => $dict["items.mother_age"],
                        "data" => "mother_age",
                    ],
                    [
                        "title" => $dict["items.notes"],
                        "data" => "notes",
                        "type" => $ROW_NOTES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.parents"],
                        "data" => "parents",
                        "type" => $ROW_PEOPLES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.children"],
                        "data" => "children",
                        "type" => $ROW_PEOPLES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.events"],
                        "data" => "events",
                        "type" => $ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.locations"],
                        "data" => "locations",
                        "type" => $ROW_LOCATIONS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.age"],
                        "data" => "age",
                    ],
                    [
                        "title" => $dict["items.gender"],
                        "data" => "gender",
                        "type" => $ROW_TYPE
                    ],
                    [
                        "title" => $dict["items.tribe"],
                        "data" => "tribe",
                        "type" => $ROW_TYPE
                    ],
                    [
                        "title" => $dict["items.profession"],
                        "data" => "profession",
                    ],
                    [
                        "title" => $dict["items.nationality"],
                        "data" => "nationality",
                    ],
                    [
                        "title" => $dict["items.book_start"],
                        "type" => $ROW_BOOK_START
                    ],
                    [
                        "title" => $dict["items.book_end"],
                        "type" => $ROW_BOOK_END
                    ],
                ]
            ]);
        }
        
        private function MapTable() {
            global $dict, $TYPE_FAMILYTREE;
            return new MapTable([
                "title" => $dict["items.details.familytree"],
                "type" => $TYPE_FAMILYTREE
            ]);
        }
    }