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
        setButtonLeft(buttonBar);
        setButtonApp(buttonBar);
        setButtonAlp(buttonBar);
        setButtonRight(buttonBar);

        /* Show a list of the available items in the item bar
           When clicked, it will show information about this item. */
        var itemBar = document.createElement("div");
        left.appendChild(itemBar);
        
        // Set its attributes
        itemBar.id = "item_bar";
        itemBar.className = "item_" + session_settings["theme"];
        
        // Show the current page
        var page = session_settings["page"] ? session_settings["page"] : 0;
        getItemFromDatabase("peoples", "", "", page).then(showPeopleList, console.log);
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
        
        var defaultText = document.createElement("div");
        right.appendChild(defaultText);
        
        // Set its attributes
        defaultText.id = "default";
        defaultText.innerHTML = "<?php echo $dict_Peoples["default_people"]; ?>";

        // Show the selected person, when someone is selected
        if (session_settings.hasOwnProperty("id")) {
            getItemFromDatabase("peoples", session_settings["id"]).then(showPeopleInfo, console.log);
        }
        
        return right;
    }
</script>