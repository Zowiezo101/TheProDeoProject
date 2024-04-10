<?php

// https://stackoverflow.com/questions/3770513/detect-browser-language-in-php    
function prefered_language(array $available_languages) {

    $http_accept_language = filter_input(INPUT_SERVER, "HTTP_ACCEPT_LANGUAGE");
    
    // Change the keys and values around.
    // The keys become the values (integer indexes in this case) and
    // the values become the keys (abbreviated languages)
    $available_languages_flipped = array_flip($available_languages);

    // Languages that are supported AND requested, including the priority values
    $langs = [];
    
    // The languages that are supported AND requested
    $langsSet = null;
    
    // Accepted languages by browser
    $matches = null;
    
    /*
        The variable $http_accept_language can contain string like:
        fr-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5
        
        This weird combinations of characters is a regular expression:
        It is divided into groups, using the ( and )
        
        Group 1: ([\w-]+)
        Group 2: (?:[^,\d]+([\d.]+))?
        Group 3: ([\d.]+)
        
        Group 1:
        \w means, look for a to z, A to Z and _
        [\w-] means, look for either (range described above) or a dash
        The plus means to look for groups of character that match what is described in the previous line
        
        So according to the example, the first group would contain:
        fr-CH     fr        en         de        q
        
        Group 3:
        \d means, look for 0 to 9. 
        [\d.]+ means, look for a group of characters that contain a dot or a digit.
        According to the example:
        0.9        0.8        0.7        0.5
        
        Group 2:
        ^ means not to look for the specified characters.
        [^,\d]+ means to ignore groups of characters containing a digit or a comma.
        According to the example:
        fr-CH    .    fr;q=    .    en;q=    .    de;q=    .    *;q=    .
        
        But since group 3 is IN group 2, the results of group 3 are also taken into account.
        This means that the match is only valid if it is followed by any of the results of group 3.
        According to the example: ( and ) means group 3.
        fr;q=(0.9)    en;q=(0.8)    de;q=(0.7)     *;q=(0.5)    

        For the full matches, all these groups need to be combined.
        The ? behind group 2, means that group 2 doesn't necasserily needs a match for a full match
        So a full match is when group 1 has a match, and possibly followed by any of te results of group 2.
        According to the example: [ and ] means group 2, ( and ) means group 3.
        fr-CH    fr[;q=(0.9)]    en[;q=(0.8)]    de[;q=(0.7)]     [*;]q[=(0.5)]
        
        Since group 2 contains the character combination ?:, the results of group two itself are not saved.
        Only the results of group 1 and group 3.
        According to the example:
        Full matches:    fr-CH    fr[;q=(0.9)]    en[;q=(0.8)]    de[;q=(0.7)]    [*;]q[=(0.5)]
        Group 1:        fr-CH    fr                en                de                q
        Group 3:                0.9                0.8                0.7                0.5
        
        These results are stored in the variable $matches. Each result in it's own index ($match).
        The full matches in sub index [0], group 1 in sub index [1] and group 3 in sub index [2]
        
    */
    preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
    foreach($matches as $match) {

        // Split results that have a dash in it. The array('', '') part is to
        // prevent errors if there is no dash.
        // According to the example (fr-CH)
        // $a = 'fr'        $b = 'CH'
        $a = (explode('-', $match[1]) + ['', ''])[0];
        $value = isset($match[2]) ? (float) $match[2] : 1.0;

        // If the requested language is in the list of supported languages
        // Put it in the list of requested AND supported languages.
        // Save the priority value, to see what language is highest requested
        if(isset($available_languages_flipped[$a])) {
            $langs[$a] = $value;
        }

    }
    
    // If there are any matches, sort them using the values (not the keys) of the arrays.
    // Start with the greatest value and end with the smallest value
    if (count($langs) > 0) {
        arsort($langs);
        
        // Add them to the list of supported AND requested languages 
        // without saving the priority values
        foreach ($langs as $lang => $value) {
            $langsSet[] = $lang;
        }
    } else {
        // If there are no matches, use the most known language English
        $langsSet[] = "en";
    }

    return $langsSet;
}

function get_available_langs() {
    // List of available languages
    $lang_set = null;

    // Check all the available translation files
    $langFiles = glob("./translations/*.php");
    foreach ($langFiles as $filename) {
        // Take the two-letter language abbreviation
        $lang = substr($filename, -6, 2);
        
        // And add it to the list of available languages
        $lang_set[] = $lang;
    }

    return $lang_set;
}

// Set the language to a prefered language, if available
if (filter_input(INPUT_GET, "lang") === null) {
    // Languages we support
    $available_languages = get_available_langs();

    // Language settings of the browser AND supported by the website
    $langs = prefered_language($available_languages);
    
    // Most prefered language, link to this language
    header("Location: ".$base_url."/".$langs[0]."/", true, 302);
    
    exit();
}

// Get the correct translation file, that corresponds with the prefered language
$page_lang = filter_input(INPUT_GET, "lang");
require "locale/translation_".$page_lang.".php";