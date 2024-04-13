<?php 
    function onPageLoad() {
        global $id;
        return "onLoad".ucfirst($id)."();";
    }
?>

<!-- For the sidebar used with many pages -->
<script src="/src/tools/client/items.js"></script>

<script>
    // Function to load the content in the content div
    function onLoadBooks() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(getItemsMenu())
                    // The column with the selected content 
                    .append(getContentDiv())
            )
        );
        
        // Depending on the selected book, 
        // we need to get information from the database first
        getItemsContent();
    }
    
    

/* global dict */

function getBookContent(book) {
    if (book.hasOwnProperty('id')) {
    
        // A book has been selected, show its information
        $("#item_content").append(`
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">` + book.name + `</h1>
                    <p class="lead">` + book.summary + `</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">` + dict["items.details"] + `</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>` +
                                insertDetail(book, "num_chapters") + 
                                insertDetail(book, 'notes') + 
                            `</tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);
        
    } else {
        // Error message, because database can't be reached
        $("#item_content")
                .addClass("text-center")
                .append(dict["settings.database_err"]);
    }
}
</script>
