<?php    
    require "src/tools/lang.php";
    
// Needed for testing purposes
$base_url = (filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost") ? 
                "http://localhost" : 
                "https://prodeodatabase.com";

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setParameters($url) {
    global $base_url;
    $newUrl = $url;
    
    // TODO: Get languague from window.location.url
    if (filter_input(INPUT_GET, "lang")) {
        $newUrl = filter_input(INPUT_GET, "lang")."/".$url;
    }
    
    return $base_url."/".$newUrl;
}

function insertLanguages() {
    global $base_url;
    
    // Get all the currently available languages
    $languages = get_available_langs();
    
    // And everything from the base (except the first slash)
    $uri = substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1);
    
    // Create a link per language
    $links = [];
    for ($i = 0; $i < count($languages); $i++) {
        // TabIndex is purely to get the style as if href was set
        $links[] = "<a tabindex='0' class='font-weight-bold' onclick=\"setLanguage(".
                            "'".$languages[$i]."',".
                            "'".$base_url."',".
                            "'".$uri."')\">".strtoupper($languages[$i])."</a>";
    }
    
    // Insert all the links
    echo implode(" | ", $links);
}

// Page is loaded server side, let's see if we changed to a different page id
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
    <script>
        var session_settings = {
            <?php 
            if (isset($_SESSION)) {
                foreach($_SESSION as $key => $value) {
                    // We don't want all the session settings in here
                    if (!in_array($key, ["page_id", "page_id_old"])) {
                        echo "'".$key."': `".$value."`,\n\t\t";
                    }
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

        // Needed for testing purposes
        var base_url = "<?php echo $base_url; ?>";

        var page_id = "<?php echo $id; ?>";
    </script>