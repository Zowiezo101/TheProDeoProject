<?php 
    // Make it easier to copy/paste code or make a new file
    // Less chance for errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'page/template.php';
?>

<script>
    // Function to load the content in the content div
    function onLoadWorldmap() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(getItemsMenu())
                    // The column with the selected content 
                    .append(getContentDiv())
            )
        );
        
        // Show the WorldMap
        showMap();
    }
</script>