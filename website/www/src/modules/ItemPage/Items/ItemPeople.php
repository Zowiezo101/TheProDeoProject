<?php

    namespace Items;
    
    use Shapes\Title;
    use List\ItemModal;
    use Content\ItemTable;
    use Content\MapTable;

    class ItemPeople extends Item {
        public function __construct() {
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $item_list = $this->createItemList([
                "type" => TYPE_PEOPLE,
                "base_url" => "peoples/people"
            ]);
            
            $item_content = $this->createItemContent([
                "type" => TYPE_PEOPLE
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
                        "name" => "age",
                        "type" => ItemModal::INPUT_SLIDER
                    ],
                    [
                        "name" => "parent_age",
                        "type" => ItemModal::INPUT_SLIDER
                    ],
                    [
                        "name" => "gender",
                        "type" => ItemModal::INPUT_SELECT
                    ],
                    [
                        "name" => "tribe",
                        "type" => ItemModal::INPUT_SELECT
                    ],
                    [
                        "name" => "profession",
                        "type" => ItemModal::INPUT_TEXT
                    ],
                    [
                        "name" => "nationality",
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
                        "title" => $dict["items.meaning_name"],
                        "data" => "meaning_name",
                    ],
                    [
                        "title" => $dict["items.aka"],
                        "type" => \Content\ROW_AKA
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
                        "type" => \Content\ROW_NOTES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.parents"],
                        "data" => "parents",
                        "type" => \Content\ROW_PEOPLES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.children"],
                        "data" => "children",
                        "type" => \Content\ROW_PEOPLES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.events"],
                        "data" => "events",
                        "type" => \Content\ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.locations"],
                        "data" => "locations",
                        "type" => \Content\ROW_LOCATIONS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.age"],
                        "data" => "age",
                    ],
                    [
                        "title" => $dict["items.gender"],
                        "data" => "gender",
                        "type" => \Content\ROW_TYPE
                    ],
                    [
                        "title" => $dict["items.tribe"],
                        "data" => "tribe",
                        "type" => \Content\ROW_TYPE
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
                        "type" => \Content\ROW_BOOK_START
                    ],
                    [
                        "title" => $dict["items.book_end"],
                        "type" => \Content\ROW_BOOK_END
                    ],
                ]
            ]);
        }
        
        private function createMapTable() {
            global $dict;
            return new MapTable([
                "title" => $dict["items.details.familytree"],
                "type" => TYPE_FAMILYTREE
            ]);
        }
    }
