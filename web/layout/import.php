        <!-- The translation files -->
        <script src="translations/translation_<?php echo $_SESSION["lang"]; ?>.js"></script>
        
        <!-- Tools for general things -->
        <script src="tools/base.js"></script>
        <script src="tools/item.js"></script>
        <script src="tools/database.js"></script>
        
        <!-- Helpers for layout of pages -->
        <?php if (in_array($_SESSION["table"], ["peoples", "locations", "specials", "books", "events"])) { ?>
        <script src="helpers/item_info.js"></script>
        <script src="helpers/item_list.js"></script>
        <?php } ?>
        <script src="helpers/<?php echo $id; ?>.js"></script>
