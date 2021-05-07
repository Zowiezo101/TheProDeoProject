

function getBookContent(books) {
    if (books && (books.data.self.length > 0)) {
    
        // A book has been selected, show it's information
        $("#item_content").append(`
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">` + books.data.self[0].name + `</h1>
                    <p class="lead">` + books.data.self[0].summary + `</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">` + dict["items.details"] + `</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>` +
                                insertDetail(books.data.self[0], "author") + 
                                insertDetail(books.data.self[0], "written_in") + 
                                insertDetail(books.data.self[0], "num_chapters") + 
                            `</tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);
        
    } else {
        // TODO Foutmelding, niet kunnen vinden?
    }
}