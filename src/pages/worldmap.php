<?php 
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