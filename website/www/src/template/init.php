<?php    
    /*
     * This file is used to load everything that is needed to get the basics running.
     * It loads in all the needed PHP files, get the basic variables needed and
     * sets initial settings
     */

    /* Loading in all the needed PHP files */
    require "src/tools/lang.php";
    require "src/tools/base.php";
    require "src/tools/database.php";
    require "../settings.conf";
    
    /* Getting the basic variables needed */
    $base_url = (filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost") ? 
                    "http://localhost" : 
                    $domain_name;
    
    $data_base_url = setParameters("");

    /* Setting initial settings 
     * This means linking to the current language and page if we aren't
     * on these pages yet
     */
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
    
    // Set the language to a prefered language, if available
    if (filter_input(INPUT_GET, "lang") === null) {
        // Languages we support
        $available_languages = get_available_langs();

        // Language settings of the browser AND supported by the website
        $langs = prefered_language($available_languages);

        $lang = $langs[0];
        $uri = filter_input(INPUT_SERVER, "REQUEST_URI");

        // Most prefered language, link to this language
        header("Location: /".$lang.$uri, true, 302);

        exit();
    }

    // Get the correct translation file, that corresponds with the prefered language
    $page_lang = filter_input(INPUT_GET, "lang");
    require "locale/translation_".$page_lang.".php";

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

    // Page is loaded server side, let's see if we changed to a different page id
    // If we have, remove some saved settings that do not need to remain on other
    // pages
    if (isset($_SESSION["page_id"])) {

        // Save the old page id
        $_SESSION["page_id_old"] = $_SESSION["page_id"];

        // The actual check for page change
        if ($_SESSION["page_id_old"] !== $page_id) {

            // If we are logged in, save the login details
            if (isset($_SESSION["loggedin"])) {
                $loggedin = $_SESSION["loggedin"];
                $user_id = $_SESSION["user_id"];
                $user_name = $_SESSION["user_name"];
            }

            // Save a few settings and empty out the rest
            $_SESSION = [
                "page_id" => $_SESSION["page_id"],
                "page_id_old" => $_SESSION["page_id_old"],
            ];

            if (isset($loggedin)) {
                // Restore the login details
                $_SESSION["loggedin"] = $loggedin;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_name"] = $user_name;
            }
        }
    }

    // Save the page id
    $_SESSION["page_id"] = $page_id;
