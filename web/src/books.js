
function getBooksMenu() {
    var menu = $("<div>").addClass("col-md-4 col-lg-3").append(`
        <!-- Search bar and sorting -->
        <div class="row mb-2">
            <div class="col-8">
                <div class="input-group w-100">
                    <input type="text" class="form-control" id="book_search" placeholder="Search" onkeyup="searchBooks()">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="searchBooks()">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
    
            <div class="col-4">
                <div class="btn-group w-100">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Order </button>
                    <div class="dropdown-menu" id="book_sort"> 
                        <!-- We'll get the list as soon as we know the sort -->
                    </div>
                </div>
            </div>
        </div>
    
        <!-- The list of items -->
        <div class="row">
            <div class="col-md-12">
                <div class="list-group text-center" id="book_list">
                    <!-- We'll get the list as soon as we know the page and sort -->
                </div>
            </div>
        </div>
    
        <!-- Pagination -->
        <div class="row mt-2" id="book_pagination">
            <div class="col-md-12">
                <ul class="pagination mt-2 mb-2" id="book_pages">
                    <!-- We'll update pagination as soon as we know the amount of results -->
                </ul>
            </div>
        </div>
    `);
    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation
        insertAll();
    });
    
    return menu;
}

function getBooksContent() {
    
    if (get_settings["id"]) {
        getBooks(get_settings["id"]).then(function(books) {
            // A book has been selected, show it's information
            var content = $("#book_content").append(`
                <div class="row">
                    <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                        <h1 class="mb-3">` + books.data[0].name + `</h1>
                        <p class="lead">` + books.data[0].summary + `</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-11 px-lg-5 px-md-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Jacob</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Larry</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `);
        });
    } else {
        // No book has been selected, show default information
        var content = $("#book_content").append(`
            <div class="row mb-5 pb-5 text-center">
                <div class="col-lg-11 px-lg-5 px-md-3">
                    <h1 class="mb-3">Books</h1>
                    <p class="lead">Then, my friend, when darkness overspreads my eyes, and heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing, Oh, would I could describe these conceptions, could impress upon paper all that is living so full and warm within me, that it might be the mirror of my soul, as my soul is the mirror of the infinite God!&nbsp;</p>
                </div>
            </div>
        `);
    }
}

function insertAll() {
    insertSearch();
    insertSorts();
    insertBooks();
    insertPages();
}

function insertSearch() {
    $("#book_search").val(session_settings["search"] ? session_settings["search"] : "");
}

function insertSorts() {
    // Get the settings
    var currentSort = session_settings["sort"] ? 
                      session_settings["sort"] : "order_id asc";
                      
    // Start out clean
    $("#book_sort").empty();
    
    // Insert the sorts
    $("#book_sort").append('<a class="dropdown-item active" onclick="setSort0to9()"> Bible order (ascending) </a>');
    $("#book_sort").append('<a class="dropdown-item" onclick="setSort9to0()"> Bible order (descending) </a>');
    $("#book_sort").append('<a class="dropdown-item" onclick="setSortAtoZ()"> Alphabetic (ascending) </a>');
    $("#book_sort").append('<a class="dropdown-item" onclick="setSortZtoA()"> Alphabetic (descending) </a>');
}

function insertBooks() {
    
    // Get the settings
    var currentPage = session_settings["page"] ? 
                      session_settings["page"] : 0;
    var currentSort = session_settings["sort"] ? 
                      session_settings["sort"] : "order_id asc";
    var currentSearch = session_settings["search"] ? 
            "name % " + session_settings["search"] : "";

    // Get a page of books. A page is 10 items long
    // Sort by the chosen sorting method
    // Filter by the search term
    getBooks(null, {
        "limit": pageSize,
        "offset": currentPage*pageSize,
        "sort": currentSort,
        "filters": currentSearch
    }).then(function (result) {
        // Start out clean
        $("#book_list").empty();
        
        // No errors and at least 1 item of data
        if ((result.error == null) && result.data && result.data.length > 0) {
            
            // Insert all the books of a page
            for (var i = 0; i < pageSize; i++) {
                
                if (i < result.data.length) {
                    var book_obj = result.data[i];
                    var active = (book_obj.book_id == get_settings["id"]) ? "active" : "";
                    $("#book_list").append('<a href="' + setParameters('/books/book/' + book_obj.book_id) + '" class="list-group-item list-group-item-action ' + active + '"> ' + book_obj.name + ' </a>');
                } else {
                    $("#book_list").append('<a class="list-group-item list-group-item-action invisible"> empty </a>');
                }
            }
        } else {
            // TODO:
            // Error melding geven dat database niet bereikt kan worden
            $("#book_list").append(result.error ? result.error : "No results found");
        }
    });
}

function insertPages() {   
    
    // Get the settings
    var currentPage = session_settings["page"] ? 
                      session_settings["page"] : 0;
    var currentSort = session_settings["sort"] ? 
                      session_settings["sort"] : "order_id asc";
    var currentSearch = session_settings["search"] ? 
            "name % " + session_settings["search"] : "";

    // Count the amount of items
    // Sort by the chosen sorting method
    // Filter by the search term
    getBooks(null, {
        "sort": currentSort,
        "filters": currentSearch,
        "calculations": "count"
    }).then(function (result) {
        
        // No errors and at least 1 item of data
        if ((result.error == null) && result.data && result.data.length > 0) {
            // Start out clean
            $("#book_pages").empty();
    
            // Count the amount of pages
            var count = Math.ceil(result.data[0].count / pageSize);
            
            // Insert all the pages
            for (var i = 0; i < count; i++) {
                var active = (i == currentPage) ? "active" : "";
                $("#book_pages").append(`
                    <li class="page-item font-weight-bold w-100 ` + active + `"> 
                        <a class="page-link text-center" onclick="setPage(` + i + `)">` + (i + 1) + `</a> 
                    </li>
                `);
            }            
            
            if (count > 1) {
                // Wel paginatie
                $("#book_pagination").css("display", "");
            } else {
                // Geen paginatie
                $("#book_pagination").css("display", "none");
            }
        } else {
            // Geen paginatie
            $("#book_pagination").css("display", "none");
        }
    });
}

function searchBooks() {
    var query = $("#book_search").val();
    
    // Update the query to the session
    updateSession({
        "search": query,
        "page": null
    });
    
    // Recalculate the booklist and pagination
    insertBooks();
    insertPages();
    
}

function setSort0to9() {
    updateSession({
        "sort": "order_id asc", 
        "page": null
    });
    
    // Recalculate the booklist and pagination
    insertSorts();
    insertBooks();
    insertPages();
}

function setSort9to0() {
    updateSession({
        "sort": "order_id desc", 
        "page": null
    });
    
    // Recalculate the booklist and pagination
    insertSorts();
    insertBooks();
    insertPages();
}

function setSortAtoZ() {
    updateSession({
        "sort": "name asc", 
        "page": null
    });
    
    // Recalculate the booklist and pagination
    insertSorts();
    insertBooks();
    insertPages();
}

function setSortZtoA() {
    updateSession({
        "sort": "name desc", 
        "page": null
    });
    
    // Recalculate the booklist and pagination
    insertSorts();
    insertBooks();
    insertPages();
}

function setPage(page) {
    updateSession({"page": page});
    
    // Recalculate the booklist and pagination
    insertBooks();
    insertPages();
}