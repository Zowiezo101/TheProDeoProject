

/* global dict */

function getBookContent(book) {
    if (book) {
    
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
                            `</tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);
        
    } else {
        // Error message, because database can't be reached
        $("#item_content").append(dict["settings.database_err"]);
    }
}