<?php 
?>

<script>
    // Function to load the content in the content div
    function onLoadSearch() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(getSearchMenu())
                    // The column with the selected content 
                    .append(getSearchContent())
            )
        );
    }
</script>