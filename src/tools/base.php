<?php    

    function setParameters($url, $lang=false) {
        global $base_url;
        $newUrl = $url;

        if ($lang != false) {
            // Use a different language            
            $newUrl = $lang."/".$url;
        } else if (filter_input(INPUT_GET, "lang")) {
            // Use the language that is already set
            $newUrl = filter_input(INPUT_GET, "lang")."/".$url;
        }

        return $base_url."/".$newUrl;
    }

    function insertLanguages() {
        global $base_url;

        // Get all the currently available languages
        $languages = get_available_langs();

        // Get the URI, but without the first slash and language
        $uri = substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1);
        if (filter_input(INPUT_GET, "lang")) {
            $lang = filter_input(INPUT_GET, "lang");
            $uri = substr($uri, strlen($lang) + 1);
        }

        // Create a link per language
        $links = [];
        for ($i = 0; $i < count($languages); $i++) {
            // TabIndex is purely to get the style as if href was set
            $links[] = "<a tabindex='0' class='font-weight-bold' ".
                            "href='".setParameters($uri, $languages[$i])."'>".
                            strtoupper($languages[$i])."</a>";
        }

        // Insert all the links
        echo implode(" | ", $links);
    }

    // Page is loaded server side, let's see if we changed to a different page id
    // The only reason for this is to decide whether we want to keep or ditch
    // the saved sort, search term and current page of the side bar
    // TODO: Somehting else here possible so we can ditch this code?
    if (isset($_SESSION["page_id"])) {
        // Save the old page id
        $_SESSION["page_id_old"] = $_SESSION["page_id"];

        // The actual check for page change
        if ($_SESSION["page_id_old"] !== $id) {
            unset($_SESSION["sort"]);
            unset($_SESSION["search"]);
            unset($_SESSION["page"]);
        }
    }

    // Save the page id
    $_SESSION["page_id"] = $id;

?>