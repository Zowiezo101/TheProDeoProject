<?php

require "../../../settings.conf";

// The items to be used by all classes
require "../Classes/Database.php";
require "../Classes/Item.php";
require "../Classes/Link.php";
require "../Classes/Message.php";
require "../Classes/Options.php";

// The different objects to be used
require "../Classes/Activity.php";
require "../Classes/Blog.php";
require "../Classes/Book.php";
require "../Classes/Event.php";
require "../Classes/People.php";
require "../Classes/Location.php";
require "../Classes/Special.php";
require "../Classes/Timeline.php";
require "../Classes/Familytree.php";
require "../Classes/Worldmap.php";

// show error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);
    
// Needed for testing purposes
$base_url = (filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost") ? 
                "http://localhost" : 
                $domain_name;
