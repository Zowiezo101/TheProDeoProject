<?php

    class Link {
        // TODO: Do this with the base.php file as well
        
        public const BOOKS_TO_NOTES = ["book", "notes"];
        public const EVENTS_TO_NEXT = ["event", "children"];
        public const EVENTS_TO_PREV = ["event", "parents"];
        public const EVENTS_TO_PEOPLES = ["event", "peoples"];
        public const EVENTS_TO_LOCATIONS = ["event", "locations"];
        public const EVENTS_TO_SPECIALS = ["event", "specials"];
        public const EVENTS_TO_AKA = ["event", "aka"];
        public const EVENTS_TO_NOTES = ["event", "notes"];
        public const ACTIVITIES_TO_AKA = ["activity", "aka"];
        public const ACTIVITIES_TO_NOTES = ["activity", "notes"];
        public const PEOPLES_TO_PARENTS = ["people", "parents"];
        public const PEOPLES_TO_CHILDREN = ["people", "children"];
        public const PEOPLES_TO_EVENTS = ["people", "events"];
        public const PEOPLES_TO_AKA = ["people", "aka"];
        public const PEOPLES_TO_LOCATIONS = ["people", "locations"];
        public const PEOPLES_TO_NOTES = ["people", "notes"];
        public const LOCATIONS_TO_AKA = ["location", "aka"];
        public const LOCATIONS_TO_PEOPLES = ["location", "peoples"];
        public const LOCATIONS_TO_EVENTS = ["location", "events"];
        public const LOCATIONS_TO_NOTES = ["location", "notes"];
        public const SPECIALS_TO_EVENTS = ["special", "events"];
        public const SPECIALS_TO_NOTES = ["special", "notes"];
        
        // The default tables
        public const TABLE_BOOKS = "books";
        public const TABLE_EVENTS = "events";
        public const TABLE_ACTIVITIES = "activitys";
        public const TABLE_PEOPLES = "peoples";
        public const TABLE_LOCATIONS = "locations";
        public const TABLE_SPECIALS = "specials";
        public const TABLE_NOTES = "notes";
        public const TABLE_SOURCES = "sources";

        // The linking tables
        public const TABLE_A2A = "activity_to_aka";
        public const TABLE_A2PA = "activity_to_parent";
        public const TABLE_A2E = "activity_to_event";
        public const TABLE_E2E = "event_to_aka";
        public const TABLE_E2PA = "event_to_parent";
        public const TABLE_P2A = "people_to_activity";
        public const TABLE_P2P = "people_to_aka";
        public const TABLE_P2PA = "people_to_parent";
        public const TABLE_P2L = "people_to_location";
        public const TABLE_L2L = "location_to_aka";
        public const TABLE_L2A = "location_to_activity";
        public const TABLE_N2S = "note_to_source";
        public const TABLE_N2O = "note_to_item";
        public const TABLE_S2A = "special_to_activity";

        // The type tables
        public const TABLE_TN = "type_note";
        public const TABLE_TI = "type_item";
        public const TABLE_TG = "type_gender";
        public const TABLE_TP = "type_people";
        public const TABLE_TT = "type_tribe";
        public const TABLE_TL = "type_location";
        public const TABLE_TS = "type_special";

//                    case "people_to_aka":
//                        $columns = [
//                            "people_id" => false,
//                            "people_name" => true,
//                            "meaning_name" => true,
//                        ];
//                        $item_name = "people";
//                        break;
//
//                    case "location_to_aka":
//                        $columns = [
//                            "location_id" => false,
//                            "location_name" => true,
//                            "meaning_name" => true,
//                        ];
//                        $item_name = "location";
//                        break;
//
//                    case "notes":
//                        $columns = [
//                            "id" => false,
//                            "note" => true,
//                            "type" => false,
//                        ];
//                        break;
    }
