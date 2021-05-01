
function getBooksMenu() {
    var menu = $("<div>").addClass("col-3").append(`
        <!-- Search bar and sorting -->
        <div class="row mb-2">
            <div class="col-md-8">
                <form class="form-inline">
                    <div class="input-group">
                        <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 pl-0">
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropdown </button>
                    <div class="dropdown-menu"> 
                        <a class="dropdown-item" href="#">Action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- The list of items -->
        <div class="row">
            <div class="col-md-12">
                <div class="list-group text-center" id="book_list">
                    
                </div>
            </div>
        </div>
    
        <!-- Pagination -->
        <div class="row">
            <div class="col-md-12">
                <ul class="pagination mt-2 mb-2">
                    <li class="page-item font-weight-bold mx-auto bg-light"> <a class="page-link text-primary" href="#">Prev</a> </li>
                    <li class="page-item font-weight-bold"> <a class="page-link" href="#">1</a> </li>
                    <li class="page-item font-weight-bold"> <a class="page-link" href="#">2</a> </li>
                    <li class="page-item font-weight-bold active"> <a class="page-link" href="#">3</a> </li>
                    <li class="page-item font-weight-bold"> <a class="page-link" href="#">4</a> </li>
                    <li class="page-item font-weight-bold mx-auto"> <a class="page-link" href="#">Next</a> </li>
                </ul>
            </div>
        </div>
    `);
    
    insertBookPage();
    
    return menu;
}

function getBooksContent() {
    
    if (get_settings["id"]) {
        // A book has been selected, show it's information
        var content = $("<div>").addClass("col-8").append(`
            <div class="row">
                <div class="col-md-10 text-center">
                    <h1 class="mb-3">O my friend</h1>
                    <p class="lead">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
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
    } else {
        // No book has been selected, show default information
        var content = $("<div>").addClass("col-8").append(`
            <div class="row mb-5 pb-5 text-center">
                <div class="col-md-10">
                    <h1 class="mb-3">Books</h1>
                    <p class="lead">Then, my friend, when darkness overspreads my eyes, and heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing, Oh, would I could describe these conceptions, could impress upon paper all that is living so full and warm within me, that it might be the mirror of my soul, as my soul is the mirror of the infinite God!&nbsp;</p>
                </div>
            </div>
        `);
    }
    
    return content;
}

function insertBookPage() {
    var currentPage = session_settings["page"] ? session_settings["page"] : 0;
    var currentSort = session_settings["sort"] ? session_settings["sort"] : "order_id asc";

    getBooks(null, {
        "limit": 15,
        "offset": currentPage*15,
        "sort": currentSort
    }).then(function (result) {
        if ((result.error == null) && result.data && result.data.length > 0) {
            for (var i = 0; i < result.data.length; i++) {
                var book_obj = result.data[i];
                $("#book_list").append('<a href="/books/book/' + book_obj.book_id + '" class="list-group-item list-group-item-action"> ' + book_obj.name + ' </a>');
            }
        } else {
            // TODO:
            // Error melding geven dat database niet bereikt kan worden
        }
    });
}