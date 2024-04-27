<?php 
    require "content_base.php";
    
    // All the pages that use the single window template
    $single = ["search", "aboutus", "contact"];
    
    // All the pages that use the double window template
    $double = ["books", "events", "peoples", "locations", "specials"];
    
    // All the pages that use the tabs window template
    $tabs = ["login", "settings"];
    
    // All the pages that use the map window template
    $map = ["timeline", "familytree", "worldmap"];
    
    $style = "background-color: hsl(0, 100%, 99%);";
    if ($page_id == "home") {
        // The home page style is the only exception
        $style = $style . "
            background-image: url(img/background_home.svg); 
            background-position: top left; 
            background-size: 100% 32px;
            background-repeat: repeat-y";
    } 
    
    $classes = "py-5 flex-grow-1";
    if (array_search($page_id, $double) !== false) {
        $classes = "flex-grow-1";
    }
?>
        <div id="content" class="<?= $classes; ?>" style="<?= $style; ?>">
<?php
    if (array_search($page_id, $map) !== false) {
        // Map template for all map pages
        require "src/template/content_map.php";
    } else if (array_search($page_id, $double) !== false) {
        // Double template for all pages that have a sidebar
        require "src/template/content_double.php";
    } else if (array_search($page_id, $tabs) !== false) {
        // Tabs template for all pages that use tabs
        require "src/template/content_tabs.php";
    } else {
        // Just use single for anything else
        require "src/template/content_single.php";
    }
?>
        </div>
        