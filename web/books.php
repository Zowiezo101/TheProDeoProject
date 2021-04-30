<?php 
    // Make it easier to copy/paste code or make a new file
    // Less change of errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'page/template.php';
?>

<script>
    // Function to load the content in the content div
    function onLoadBooks() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(getBooksMenu())
                    // A random column to keep the space in between
                    .append(`<div class="col-md-1"></div>`)
                    // The column with the selected content
                    .append(getBooksContent())
            )
        );
    }
</script>
