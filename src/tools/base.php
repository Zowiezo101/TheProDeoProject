<?php    
    /*
     * This file contains all the functions that are needed throughout the whole
     * website
     */

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
