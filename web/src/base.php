<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setParameters($url) {
    $newUrl = $url;
    
    // TODO: Get languague from window.location.url
    if (filter_input(INPUT_GET, "lang")) {
        $newUrl = filter_input(INPUT_GET, "lang")."/".$url;
    }
    
    return "http://".filter_input(INPUT_SERVER, 'SERVER_NAME')."/".$newUrl;
}

function insertLanguages() {
    // Get all the currently available languages
    $languages = ["nl", "en"];
    
    // And everything from the base (except the first slash)
    $uri = substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1);
    
    // Create a link per language
    $links = [];
    for ($i = 0; $i < count($languages); $i++) {
        // TabIndex is purely to get the style as if href was set
        $links[] = "<a tabindex='0' class='font-weight-bold' onclick=\"setLanguage(".
                            "'".$languages[$i]."',".
                            "'http://".filter_input(INPUT_SERVER, 'SERVER_NAME')."',".
                            "'".$uri."')\">".strtoupper($languages[$i])."</a>";
    }
    
    // Insert all the links
    echo implode(" | ", $links);
}

?>

<script>
    var session_settings = {
        <?php 
        if (isset($_SESSION)) {
            foreach($_SESSION as $key => $value) {
                echo "'".$key."': '".$value."',\n\t\t";
            }
        }?>
    };
    var get_settings = {
        <?php  
        $input_get = filter_input_array(INPUT_GET);
        if ($input_get) {
            foreach($input_get as $key => $value) {
                echo "'".$key."': `".$value."`,\n\t\t";
            }
        }?>
    };
    var post_settings = {
        <?php 
        $input_post = filter_input_array(INPUT_POST);
        if ($input_post) {
            foreach($input_post as $key => $value) {
                echo "'".$key."': `".$value."`,\n\t\t";
            }
        }?>
    };
</script>