<?php 
    $id = "worldmap";
    require "layout/template.php"; 
?>

<script>
    
    function onLoadWorldmap() {
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
         
        if (session_settings.hasOwnProperty('disp_error')) {
            if (session_settings['disp_error'] !== "") {
                // If there is an error, display it!
                var Error = document.createElement('p');
                Error.innerHTML = session_settings['disp_error'];
                right.appendChild(Error);
                
                updateSessionSettings("disp_error", "");
            }
        }
    }
    
</script>