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
                    // The column with the selected content 
                    // (to be filled up)
                    .append($("<div>").addClass("col-md-8 col-lg-9")
                                     .attr("id", "book_content"))
            )
        );
        
        // Depending on the selected book, 
        // we need to get information from the database first
        getBooksContent();
    }
</script>
