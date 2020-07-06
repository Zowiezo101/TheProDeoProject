        <!-- The translation files -->
        <script src="translations/translation_<?php echo $_SESSION["lang"]; ?>.js"></script>
        
        <!-- Tools for general things -->
        <script src="tools/base.js"></script>
        <?php if (in_array($_SESSION["table"], ["peoples", "locations", "specials", "books", "events"])) { ?>
        <script src="tools/item.js"></script>
        <?php } elseif (in_array($_SESSION["table"], ["timeline", "familytree", "worldmap"])) { ?>
        <script src="tools/map.js"></script>
        <script src="map/map_draw.js"></script>
        <script src="map/map_view.js"></script>
        <script src="map/map_prep.js"></script>
        <script src="map/map_mouse.js"></script>
        <script src="map/map_set.js"></script>
        <?php } ?>
        <script src="tools/database.js"></script>
        
        <!-- Helpers for layout of pages -->
        <?php if (in_array($_SESSION["table"], ["peoples", "locations", "specials", "books", "events"])) { ?>
        <script src="helpers/item_info.js"></script>
        <script src="helpers/item_list.js"></script>
        <?php } elseif (in_array($_SESSION["table"], ["timeline", "familytree", "worldmap"])) { ?>
        <script src="helpers/map_info.js"></script>
        <script src="helpers/map_list.js"></script>
        <?php } ?>
        <script src="helpers/<?php echo $id; ?>.js"></script>
