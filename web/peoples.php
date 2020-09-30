<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "peoples";
    require 'layout/template.php';
?>

<script>    
    function onLoadPeoples() {
        
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        var content = $("#content");

        // Set left and right sides of the content div
        var left = setLeftSide(content);
        var right = setRightSide(content);

        // Set the height of the left div, to the height of the right div
        left.css("height", right.offsetHeight + "px");
        content.css("height", right.offsetHeight + "px");
    }
</script>