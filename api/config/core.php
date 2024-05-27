<?php

require "../../../settings.conf";

// The items to be used by all classes
require "../shared/Database.php";
require "../shared/Item.php";
require "../shared/Utilities.php";

// Some constants used throughout the files
// TODO: Do this with the base.php file as well
define("BOOKS_TO_NOTES", ["book", "notes"]);
define("EVENTS_TO_NEXT", ["event", "children"]);
define("EVENTS_TO_PREV", ["event", "parents"]);
define("EVENTS_TO_PEOPLES", ["event", "peoples"]);
define("EVENTS_TO_LOCATIONS", ["event", "locations"]);
define("EVENTS_TO_SPECIALS", ["event", "specials"]);
define("EVENTS_TO_AKA", ["event", "aka"]);
define("EVENTS_TO_NOTES", ["event", "notes"]);
define("ACTIVITIES_TO_AKA", ["activity", "aka"]);
define("ACTIVITIES_TO_NOTES", ["activity", "notes"]);
define("PEOPLES_TO_PARENTS", ["people", "parents"]);
define("PEOPLES_TO_CHILDREN", ["people", "children"]);
define("PEOPLES_TO_EVENTS", ["people", "events"]);
define("PEOPLES_TO_AKA", ["people", "aka"]);
define("PEOPLES_TO_LOCATIONS", ["people", "locations"]);
define("PEOPLES_TO_NOTES", ["people", "notes"]);
define("LOCATIONS_TO_AKA", ["location", "aka"]);
define("LOCATIONS_TO_PEOPLES", ["location", "peoples"]);
define("LOCATIONS_TO_EVENTS", ["location", "events"]);
define("LOCATIONS_TO_NOTES", ["location", "notes"]);
define("SPECIALS_TO_EVENTS", ["special", "events"]);
define("SPECIALS_TO_NOTES", ["special", "notes"]);
    
// The default tables
define("table_events", "events");
define("table_activities", "activitys");
define("table_peoples", "peoples");
define("table_locations", "locations");
define("table_specials", "specials");

// The linking tables
define("table_a2a", "activity_to_aka");
define("table_a2pa", "activity_to_parent");
define("table_a2e", "activity_to_event");
define("table_e2e", "event_to_aka");
define("table_e2pa", "event_to_parent");
define("table_p2a", "people_to_activity");
define("table_p2p", "people_to_aka");
define("table_l2l", "location_to_aka");
define("table_p2pa", "people_to_parent");
define("table_p2l", "people_to_location");
define("table_l2a", "location_to_activity");
define("table_s2a", "special_to_activity");
define("table_notes", "notes");
define("table_sources", "sources");
define("table_n2s", "note_to_source");
define("table_n2i", "note_to_item");

// The type tables
define("table_tn", "type_note");
define("table_ti", "type_item");
define("table_tg", "type_gender");
define("table_tp", "type_people");
define("table_tt", "type_tribe");
define("table_tl", "type_location");
define("table_ts", "type_special");

// show error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);
    
// Needed for testing purposes
$base_url = (filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost") ? 
                "http://localhost" : 
                "https://prodeodatabase.com";
  
// get database connection
$db = new shared\Database();
$conn = $db->getConnection();
