<?php 
    // All the pages that use the single window template
    $single = ["login", "settings", "search", "aboutus", "contact"];
    
    // All the pages that use the double window template
    $double = ["books", "events", "peoples", "locations", "specials"];
    
    // All the pages that use the map window template
    $map = ["timeline", "familytree", "worldmap"];
    
    $style = "background-color: hsl(0, 100%, 99%);";
    if ($id == "home") {
        // The home page style is the only exception
        $style = $style .
           "background-image: url(img/background_home.svg); 
            background-position: top left; 
            background-size: 100% 32px;
            background-repeat: repeat-y";
    }
?>

<div id="content" class="py-5 flex-grow-1" style="<?= $style; ?>">
<?php
    if (array_search($id, $map)) {
        // Map template for all map pages
        require "src/template/content_map.php";
    } else if (array_search($id, $double)) {
        // Double template for all pages that have a sidebar
        require "src/template/content_double.php";
    } else {
        // Just use single for anything else
        require "src/template/content_single.php";
    }
?>
</div>
        