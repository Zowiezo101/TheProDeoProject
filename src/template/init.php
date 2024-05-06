<?php    
    
    // The page id, this is taken from the link we are currently on. If there is no page id given, go to the home page
    $page_id = filter_input(INPUT_GET,'page') !== null ? filter_input(INPUT_GET,'page') : "home";
    if ($page_id == "") {
        // Go to the home page
        if( headers_sent() ) { 
            echo("<script>location.href='home'</script>"); 
        } else { 
            header("Location: home"); 
        }
        exit;
    }
    
    // Some basic stuff that we need to make everything work
    require "src/tools/lang.php";
    require "src/tools/base.php";
    
    // Needed for testing purposes
    $base_url = (filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost") ? 
                    "http://localhost" : 
                    "https://prodeodatabase.com";

    $dropdown = "";
    // Get the dropdown menu that needs to have it's button activated
    if (in_array($page_id, ['books', 'events', 'peoples', 'locations', 'specials', 'search'])) {
        $dropdown = "database";
    }

    // The theme that is used for this page
    switch($page_id) {
        case 'home':
        case 'search':
        case 'settings':
            $theme = "purple";
            break;

        case 'books':
        case 'aboutus':
            $theme = "pink";
            break;

        case 'events':
        case 'timeline':
            $theme = "orange";
            break;

        case 'peoples':
        case 'familytree':
            $theme = "red";
            break;

        case 'locations':
        case 'worldmap':
            $theme = "blue";
            break;

        case 'specials':
        case 'contact':
            $theme = "green";
            break;

        default:
            $theme = "purple";
            break;
    }
    
?>