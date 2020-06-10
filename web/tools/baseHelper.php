<?php

// https://stackoverflow.com/questions/3770513/detect-browser-language-in-php    
function prefered_language(array $available_languages, $http_accept_language) {

    // Change the keys and values around.
    // The keys become the values (integer indexes in this case) and
    // the values become the keys (abbreviated languages)
    $available_languages_flipped = array_flip($available_languages);

    // Languages that are supported AND requested, including the priority values
    $langs = null;
    
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
        list($a, $b) = explode('-', $match[1]) + array('', '');
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
    $langsSet = null;

    // Check all the available translation files
    $langFiles = glob("./translations/*.php");
    foreach ($langFiles as $filename) {
        // Take the two-letter language abbreviation
        $lang = substr($filename, -6, 2);
        
        // And add it to the list of available languages
        $langsSet[] = $lang;
    }

    return $langsSet;
}

// Set the language to a prefered language, if available
if (!isset($_SESSION["lang"])) {
    // Languages we support
    $available_languages = get_available_langs();

    // Language settings of the browser AND supported by the website
    $langs = prefered_language($available_languages, filter_input(INPUT_SERVER, "HTTP_ACCEPT_LANGUAGE"));
    
    // Most prefered language. Save it in the session
    $_SESSION["lang"] = $langs[0];
} 

// Get the correct translation file, that corresponds with the prefered language
$page_lang = $_SESSION["lang"];
require "translations/translation_".$page_lang.".php"; 

// Log in data, needed to connect to the database
require "../login_data.php";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Which helper file do we need? One for items or one for maps?
if (($id == "timeline") || ($id == "familytree")) {
    require "tools/mapHelper.php";
} elseif ($id == "timeline_ext") {
    // TODO: Extended events
    require "tools/mapHelper.php";    
} elseif (($id == "peoples")     || 
        ($id == "locations")     || 
        ($id == "specials")     || 
        ($id == "books")         || 
        ($id == "events")) {
        require "tools/itemHelper.php";
}

require "helpers/".$id.".php";

/* Used pretty much everywhere. 
   This function adds newlines and tabs, to make the generated HTML and Javascript
   code more readable */
function PrettyPrint($string, $firstLine = 0) {
    if ($firstLine) {
        echo $string."\r\n";
    } else {
        echo "\t\t\t".$string."\r\n";
    }
}

?>

<script>
        

    // http://stackoverflow.com/a/10997390/11236
    function updateURLParameter(url, param, paramVal){
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        
//        window.location.search
//        URLSearchParams
        
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            
            for (var i=0; i<tempArray.length; i++){
                if(tempArray[i].split('=')[0] !== param){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }
    
    function removeURLParameter(url, param){
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "?";
        
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            
            for (var i=0; i<tempArray.length; i++){
                if(tempArray[i].split('=')[0] !== param){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }
        return baseURL + newAdditionalURL;
    }
    
    
</script>