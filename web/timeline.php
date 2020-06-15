<?php
    // Make it easier to copy/paste code or make a new file
    $id = "timeline";
    require 'layout/template.php';
?>

<script>
    function onLoadTimeline() {
        
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        var content = document.getElementById("content");
        
        // This div is used to separate item_choice and item_info in two columns.
        // But resume with one column under these two columns.
        var clearFix = document.createElement("div");
        content.appendChild(clearFix);
        
        // Set the class name
        clearFix.className = "clearfix";

        // Set left and right sides of the content div
        var left = setLeftSide(clearFix);
        var right = setRightSide(clearFix);

        // Set the height of the left div, to the height of the right div
        left.setAttribute("style", "height: " + right.offsetHeight + "px");
        content.setAttribute("style", "height: " + right.offsetHeight + "px");
    }
    
    function setLeftSide(parent) {
        // Left column
        var left = document.createElement("div");
        parent.appendChild(left);

        // Set its attributes
        left.id = "item_choice";
        left.className = "contents_left";

        // Div with all the buttons for the item bar
        var buttonBar = document.createElement("div");
        left.appendChild(buttonBar);

        // Set its attributes
        buttonBar.id = "button_bar";

        // Add all the buttons to it
        // TODO:
//        setButtonLeft(buttonBar);
//        setButtonApp(buttonBar);
//        setButtonAlp(buttonBar);
//        setButtonRight(buttonBar);

        /* Show a list of the available items in the item bar
           When clicked, it will show information about this item. */
        var itemBar = document.createElement("div");
        left.appendChild(itemBar);

        // Set its attributes
        itemBar.id = "item_bar";
        itemBar.className = "item_" + session_settings["theme"];

        // Show the current page
        // TODO: getMapsFromDatabase ()
        // Alle dingen zonder ouders returnen (Tijdelijk ook zonder kinderen)
        // Als er een item gekozen is, wordt pas de benodigde informatie bij elkaar gezocht om er iets mee te bouwen
        // 
        // var page = session_settings["page"] ? session_settings["page"] : 0;
        // var sort = session_settings["sort"] ? session_settings["sort"] : "app";
        getMapsFromDatabase(session_settings["table"]).then(showItemList, console.log);
        return left;
    }
    
    function setRightSide(parent) {
        /* Right column. This is where the item info will be displayed
           when an item is clicked from the item bar. When no item is
           clicked yet, show default text with instructions. */
        var right = document.createElement("div");
        parent.appendChild(right);

        // Set its attributes
        right.id = "item_info";
        right.className = "contents_right";

        // The default text
        var defaultText = document.createElement("div");
        right.appendChild(defaultText);

        // Show the selected timeline, when someone is selected
        if (session_settings.hasOwnProperty("id")) {                        

            // Set its attributes
            defaultText.id = "default";
            defaultText.innerHTML = dict_Timeline["loading"];
            
            // The progress bar
            var progressBar = document.createElement("div");
            defaultText.appendChild(progressBar);
            
            // Set its attributes
            progressBar.id = "progress_bar";
            
            // The progress in the progress bar
            var progress = document.createElement("div");
            progressBar.appendChild(progress);
            
            // Set its attributes
            progress.id = "progress";
            progress.innerHTML = "1%";
        } else {
            // Set its attributes
            defaultText.id = "default";
            defaultText.innerHTML = dict_Timeline["default"];
        }
        
        // A SVG canvas to save the SVG
        var hidden_div = document.createElement("div");
        right.appendChild(hidden_div);
        
        hidden_div.id = "hidden_div";
        hidden_div.style = "display: none";
        
        // The SVG, canvas and link inside it
        var hidden_svg = document.createElement("svg");
        var hidden_canvas = document.createElement("canvas");
        var hidden_a = document.createElement("a");
        
        hidden_div.appendChild(hidden_svg);
        hidden_div.appendChild(hidden_canvas);
        hidden_div.appendChild(hidden_a);
        
        hidden_svg.id = "hidden_svg";
        hidden_canvas.id = "hidden_canvas";
        hidden_a.id = "hidden_a";

        return right;
    }
    
    function Helper_onLoad() {    

        // List of items
        Items = [<?php echo FindItems(); ?>];

        // Create all the connections between parents and children
        setItems();
    
        // Make a nice list here to choose from the set of ItemList Item
        // When chosen, update ItemId and redraw page
        var itemBar = document.getElementById("item_bar");
        
        <?php  
        if (isset($_SESSION['disp_error'])) {
            if ($_SESSION['disp_error'] != "") {
                // If there is an error, display it!
                PrettyPrint("var Error = document.createElement('p');");
                PrettyPrint("Error.innerHTML = '".$_SESSION['disp_error']."';");
                PrettyPrint("itemBar.appendChild(Error);");
                
                $_SESSION['disp_error'] = "";
            }
        }
        ?>
        
        var table = document.createElement("table");
        for (var i = 0; i < ItemsList.length; i++) {
            var ItemId = ItemsList[i];
            var Item = getItemById(ItemId);
            
            var TableLink = document.createElement("button");
            TableLink.onclick = UpdateLink;
            TableLink.newLink = updateURLParameter(window.location.href, "id", i + "," + Item.ID);
            TableLink.innerHTML = Item.name;
            
            var TableData = document.createElement("td");
            TableData.appendChild(TableLink);
        
            var TableRow = document.createElement("tr");
            TableRow.appendChild(TableData);
            
            table.appendChild(TableRow);
        }
        itemBar.appendChild(table);
        
        <?php if (null !== filter_input(INPUT_GET, 'id')) { ?>    
            var IDs = "<?php echo filter_input(INPUT_GET, 'id'); ?>".split(",");
            
            // Get the Map and the ID numbers
            globalMapId = Number(IDs[0]);
            globalItemId = Number(IDs[1]);
            
            prep_SetSVG();
        <?php } ?>
    }
</script>