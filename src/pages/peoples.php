<?php 
?>

<script>
    // Function to load the content in the content div
    function onLoadPeoples() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(getItemsMenu())
                    // The column with the selected content 
                    .append(getContentDiv())
            )
        );
        
        // Depending on the selected person, 
        // we need to get information from the database first
        getItemsContent();
    }
</script>
