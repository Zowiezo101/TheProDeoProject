<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "peoples";
    require 'layout/layout.php';
?>

<script>
    
    function onLoadPeoples() {
        
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        var content = document.getElementById("content");
        
        // This div is used to separate item_choice and item_info in two columns.
        // But resume with one column under these two columns.
        var clearFix = document.createElement("div");
        clearFix.className = "clearfix";
        content.appendChild(clearFix);

        // Left column
        var left = document.createElement("div");
        left.id = "item_choice";
        left.className = "contents_left";
        clearFix.appendChild(left);
        
        // Div with all the buttons for the item bar
        var buttonBar = document.createElement("div");
        buttonBar.id = "buttonBar";

        var buttonLeft = document.createElement("button");
        var buttonRight = document.createElement("button");
        var buttonAlp = document.createElement("button");
        var buttonApp = document.createElement("button");
        
        buttonLeft.id = "button_left";
        buttonRight.id = "button_right";
        buttonAlp.id = "button_alp";
        buttonApp.id = "button_app";
        
        buttonLeft.className = "button_<?php echo $peoples; ?>";
        buttonRight.className = "button_<?php echo $peoples; ?>";
        buttonAlp.className = "sort_a_z";
        buttonApp.className = "sort_9_1";
        
        buttonLeft.onClick = PrevPage;
        buttonRight.onClick = NextPage;
        buttonAlp.onClick = SortOnAlphabet;
        buttonApp.onClick = SortOnAppearance;
        
        buttonLeft.innerHTML = "←";
        buttonRight.innerHTML = "→";
        
        buttonBar.appendChild(buttonLeft);   // Previous page
        buttonBar.appendChild(buttonRight);  // Next page
        buttonBar.appendChild(buttonAlp);    // Sort on Alphabet
        buttonBar.appendChild(buttonApp);    // Sort on Apperance

        /* Show a list of the available items in the item bar
           When clicked, it will show information about this item. */
        var itemBar = document.createElement("div");
        itemBar.id = "item_bar";
        itemBar.className = "item_<?php echo $peoples; ?>";
        // 
//        GetListOfItems("peoples");

        /* Right column. This is where the item info will be displayed
           when an item is clicked from the item bar. When no item is
           clicked yet, show default text with instructions. */
        var right = document.createElement("div");
        right.id = "item_info";
        right.className = "contents_right";
        clearFix.appendChild(right);
        
        <div class="contents_right" id="item_info">
            <div id="default">
                <?php echo $dict_Peoples["default_people"]; ?>
            </div>
        </div>

        <?php if (null !== filter_input(INPUT_GET, 'id')) { ?>
        var id = '<?php echo filter_input(INPUT_GET, 'id') ?>';
        getItemFromDatabase("peoples", id, "").then(peoplesHelperLayout, console.log);
        <?php } ?>
    }
    
</script>