function getSearchMenu() {
    var menu = $("<div>").addClass("col-md-4 col-lg-3").append(`
            <!-- Search bar -->
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" id="item_name" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="searchItems()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" class="form-control w-100" id="item_meaning_name" placeholder="Betekenis naam" onkeyup="searchItems()">
                    </form>
                </div>
            </div>
    
                  <div class="row">
                    <div class="col-md-12">
                      <form class="form-inline">
                        <input type="text" class="form-control w-100" id="inlineFormInputGroup" placeholder="Name">
                      </form>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <form class="form-inline">
                        <input type="text" class="form-control w-100" id="inlineFormInputGroup" placeholder="Name">
                      </form>
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-12">
                      <div class="btn-group w-100">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Type </button>
                        <div class="dropdown-menu">
                          <form class="px-2 py-1">
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="dropdownCheck">
                              <label class="form-check-label" for="dropdownCheck"> Books </label>
                            </div>
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="dropdownCheck">
                              <label class="form-check-label" for="dropdownCheck"> Events </label>
                            </div>
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="dropdownCheck">
                              <label class="form-check-label" for="dropdownCheck"> Peoples </label>
                            </div>
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="dropdownCheck">
                              <label class="form-check-label" for="dropdownCheck"> Locations </label>
                            </div>
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="dropdownCheck">
                              <label class="form-check-label" for="dropdownCheck"> Locations </label>
                            </div>
                            <button type="button" class="btn btn-primary w-100 mt-2">All</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row pb-2">
                    <div class="col-md-6">
                      <div class="btn-group w-100">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropdown </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="btn-group w-100">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropdown </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row pb-2">
                    <div class="col-md-6">
                      <div class="btn-group w-100">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropdown </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="btn-group w-100">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropdown </button>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                      </div>
                    </div>
                  </div>
    `);
    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation
        // Insert the search terms from the session
        insertSearch();
    });
    
    return menu;
}

function getSearchContent() {
    var menu = $("<div>").addClass("col-md-8 col-lg-9").append(`
            <!-- Search explanation -->
            <div class="row text-center mb-4">
                <div class="col-lg-11 px-lg-5 px-md-3">
                    <h1 class="mb-3">I enjoy with my whole heart</h1>
                    <p>Then, my friend, when darkness overspreads my eyes, and heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing, Oh, would I could describe these conceptions, could impress upon paper all that is living so full and warm within me, that it might be the mirror of my soul, as my soul is the mirror of the infinite God!&nbsp;</p>
                </div>
            </div>
    
            <!-- Search results -->
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <!-- Tab selection -->
                    <ul class="nav nav-tabs font-weight-bold">
                        <li class="nav-item"> <a class="active nav-link" data-toggle="tab" href="" data-target="#tabbooks">` + dict["navigation.books"] + `</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabevents">` + dict["navigation.events"] + `</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabpeoples">` + dict["navigation.peoples"] + `</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tablocations">` + dict["navigation.locations"] + `</a></li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabspecials">` + dict["navigation.specials"] + `</a></li>
                    </ul>
    
                    <!-- The different tabs -->
                    <div class="tab-content mt-2">
                        <!-- Tab for books -->
                        <div class="tab-pane fade show active" id="tabbooks" role="tabpanel">
                            <p class="">When I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms.</p>
                        </div>
    
                        <!-- Tab for events -->
                        <div class="tab-pane fade" id="tabevents" role="tabpanel">
                            <p class="">Who formed us in his own image, and the breath of that universal love which bears and sustains us. And then, my friend.</p>
                        </div>
    
                        <!-- Tab for peoples -->
                        <div class="tab-pane fade" id="tabpeoples" role="tabpanel">
                            <p class="">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing.</p>
                        </div>
                        
                        <!-- Tab for locations -->
                        <div class="tab-pane fade" id="tablocations" role="tabpanel">
                            <p class="">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing.</p>
                        </div>
                        
                        <!-- Tab for specials -->
                        <div class="tab-pane fade" id="tabspecials" role="tabpanel">
                            <p class="">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing.</p>
                        </div>
                    </div>
                </div>
            </div>
    `);
    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation        
        // Insert the results with the search terms
        insertResults();
    });
    
    return menu;
}

/** Insert the search term from the session */
function insertSearch() {
    $("#item_name").val(
            session_settings["search_name"] ? 
            session_settings["search_name"] : "");
    $("#item_meaning_name").val(
            session_settings["search_meaning_name"] ? 
            session_settings["search_meaning_name"] : "");
}

function insertResults() {
    // Get all the search terms, and use them to filter out results
    var name = session_settings["search_name"] ? 
            "name % " + session_settings["search_name"] : "";
    var meaning_name = session_settings["search_meaning_name"] ? 
            "meaning_name % " + session_settings["search_meaning_name"] : "";
            
    var books_search_terms = {};
    var events_search_terms = {};
    var peoples_search_terms = {};
    var locations_search_terms = {};
    var specials_search_terms = {};
    if (name !== "") {
        books_search_terms["name"] = name;
        events_search_terms["name"] = name;
        peoples_search_terms["name"] = name;
        locations_search_terms["name"] = name;
        specials_search_terms["name"] = name;
    } if (meaning_name !== "") {
        peoples_search_terms["meaning_name"] = meaning_name;
        locations_search_terms["meaning_name"] = meaning_name;
        specials_search_terms["meaning_name"] = meaning_name;
    }

    
    // Get the data of the books, events, peoples, locations & specials 
    // using the search terms
    getBooks(null, {
        "columns": ["num_chapters"]
                        .concat(Object.keys(books_search_terms))
                        .join(", "),
        "filters": Object.values(books_search_terms)
                    .join(", ")
    }).then(function(result) { insertItems("books", result); });

    getEvents(null, {
        "columns": ["book_start_id", "book_start_chap", "book_start_vers",
                    "book_end_id", "book_end_chap", "book_end_vers"]
                        .concat(Object.keys(events_search_terms))
                        .join(", "),
        "filters": Object.values(events_search_terms)
                    .join(", ")
    }).then(function(result) { insertItems("events", result); });
    
    getPeoples(null, {
        "columns": ["book_start_id", "book_start_chap", "book_start_vers",
                    "book_end_id", "book_end_chap", "book_end_vers"]
                        .concat(Object.keys(peoples_search_terms))
                        .join(", "),
        "filters": Object.values(peoples_search_terms)
                    .join(", ")
    }).then(function(result) { insertItems("peoples", result); });
    
    getLocations(null, {
        "columns": ["book_start_id", "book_start_chap", "book_start_vers",
                    "book_end_id", "book_end_chap", "book_end_vers"]
                        .concat(Object.keys(locations_search_terms))
                        .join(", "),
        "filters": Object.values(locations_search_terms)
                    .join(", ")
    }).then(function(result) { insertItems("locations", result); });
    
    getSpecials(null, {
        "columns": ["book_start_id", "book_start_chap", "book_start_vers",
                    "book_end_id", "book_end_chap", "book_end_vers"]
                        .concat(Object.keys(specials_search_terms))
                        .join(", "),
        "filters": Object.values(specials_search_terms)
                    .join(", ")
    }).then(function(result) { insertItems("specials", result); });
}

function searchItems() {
    // The search termd inserted
    var name = $("#item_name").val();
    var meaning_name = $("#item_meaning_name").val();
    
    // Update the query to the session
    updateSession({
        "search_name": name,
        "search_meaning_name": meaning_name
    });
    
    // Recalculate the search results
    insertResults();
}

function insertItems(type, result) {
    // Start out clean
    $("#tab" + type).empty();
    
    var meaning_name_types = session_settings["search_meaning_name"] ? 
                                ["peoples", "locations", "specials"] : 
                                        [];
    
    // No errors and at least 1 item of data
    if ((result.error == null) && result.data && result.data.length > 0) {
        
        // Table header is the name
        var table_header = '<th scope="col">' + dict["items.name"] + '</th>';
        if (meaning_name_types.includes(type)) {
            table_header += '<th scope="col">' + dict["items.meaning_name"] + '</th>';
        }
        if (type != "books") {
            // Get the first and last appearance if it's available
            table_header += '<th scope="col">' + dict["items.book_start"] + '</th>';
            table_header += '<th scope="col">' + dict["items.book_end"] + '</th>';
        } else {
            // Get the number of chapters if they are available
            table_header += '<th scope="col">' + dict["items.num_chapters"] + '</th>';
        }
        table_header += '<th scope="col">Link to page</th>';
        
        table_row = [];
        for (var i = 0; i < result.data.length; i++) {
            var data = result.data[i];
            
            // Table header is the name
            table_data = '<th scope="row">' + data["name"] + '</th>';
            if (meaning_name_types.includes(type)) {
                table_data += '<td>' + data["meaning_name"] + '</td>';
            }
            // Get the first and last appearance if it's available
            if (type != "books") {
                table_data += ('<td>' + dict["books.book_" + data["book_start_id"]] + " " + data["book_start_chap"] + ":" + data["book_start_vers"] + '</td>');
                table_data += ('<td>' + dict["books.book_" + data["book_end_id"]] + " " + data["book_end_chap"] + ":" + data["book_end_vers"] + '</td>');
            } else {
                table_data += ('<td>' + data["num_chapters"] + '</td>');
            }
            
            // The link to this item
            var link = getLinkToItem(type, data.id, "self");
            table_data += ('<td>' + link + '</td>');
            
            // The row for every item we've got
            table_row.push('<tr>' + table_data + '</tr>');
        }
        
        $("#tab" + type).append(`
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>`
                            + table_header +
                        `</tr>
                    </thead>
                    <tbody>`
                        + table_row.join("") +
                    `</tbody>
                </table>
            </div>
        `);
    } else {
        // TODO:
        // Error melding geven dat database niet bereikt kan worden
        $("#tab" + type).append(result.error ? result.error : "No results found");
    }
}
    