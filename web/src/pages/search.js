var firstChapterStart = true;
var firstChapterEnd = true;

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
    
            <!-- Meaning name -->
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" class="form-control w-100" id="item_meaning_name" placeholder="` + dict["items.meaning_name"] + `" onkeyup="searchItems()">
                    </form>
                </div>
            </div>
    
            <!-- Description -->
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" class="form-control w-100" id="item_descr" placeholder="` + dict["items.descr"] + `" onkeyup="searchItems()">
                    </form>
                </div>
            </div>
    
            <!-- First appearance -->    
            <div class="row pb-2">
                <div class="col-md-12 text-center">
                    <label class="font-weight-bold" id="item_start_label">` + dict["items.book_start"] + `:
                    </label>
                </div>
    
                <div class="col-md-6">
                    <select class="custom-select" id="item_start_book" onchange="insertStartChapters()">
                        <option selected disabled value="-1">` + dict["books.book"] + `</option>
                        <!-- Filled in later -->
                    </select>
                </div>

                <div class="col-md-6">
                    <select class="custom-select" id="item_start_chap" onchange="searchItems()">
                        <option selected disabled value="-1">` + dict["books.chapter"] + `</option>
                        <!-- Filled in later -->
                    </select>
                </div>
            </div>
    
            <!-- Last appearance -->
            <div class="row pb-2">
                <div class="col-md-12 text-center">
                    <label class="font-weight-bold" id="item_end_label">` + dict["items.book_end"] + `:
                    </label>
                </div>
    
                <div class="col-md-6">
                    <select class="custom-select" id="item_end_book" onchange="insertEndChapters()">
                        <option selected disabled value="-1">` + dict["books.book"] + `</option>
                        <!-- Filled in later -->
                    </select>
                </div>

                <div class="col-md-6">
                    <select class="custom-select" id="item_end_chap" onchange="searchItems()">
                        <option selected disabled value="-1">` + dict["books.chapter"] + `</option>
                        <!-- Filled in later -->
                    </select>
                </div>
            </div>
    
            <!-- Specific search options for -->
            <div class="row my-2">
                <div class="col-md-12 text-center">
                    <label class="font-weight-bold">` + dict["search.specific_for"] + ` 
                        <a tabindex=0 onclick="removeSpecificFilter()" data-toggle="tooltip" data-placement="top" title="` + dict["search.remove_filter"] + `">[x]</a>
                    </label>
                </div>
    
                <div class="col-md-12">
                    <select class="custom-select" id="item_specific" onchange="addSpecifics()">
                        <option selected disabled value="-1">` + dict["search.select"] + `</option>
                        <option value="0">` + dict["search.none"] + `</option>
                        <option value="1">` + dict["navigation.books"] + `</option>
                        <option value="2">` + dict["navigation.events"] + `</option>
                        <option value="3">` + dict["navigation.peoples"] + `</option>
                        <option value="4">` + dict["navigation.locations"] + `</option>
                        <option value="5">` + dict["navigation.specials"] + `</option>
                    </select>
                </div>
            </div>
    `);
    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation
        $('[data-toggle="tooltip"]').tooltip()
        // Insert the dropdown items for books and chapters
        insertBooks();
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

/**
 * Insert functions for different select boxes
 * These will update the search filters, depending on the selected option
 * 
 * */
function insertBooks() {
    getBooks(null, {
       "columns": "id, name, num_chapters",
       "sort": "order_id asc",
    }).then(function(books) {
        if (!books.error && books.data && books.data.length > 0) {
            for (var i = 0; i < books.data.length; i++) {
                $("#item_start_book").append('<option data-num-chapters="' + books.data[i]["num_chapters"] + '" value="' + (i+1) + '">' + dict["books.book_" + (i+1)] + '</option>');
                $("#item_end_book").append('<option data-num-chapters="' + books.data[i]["num_chapters"] + '" value="' + (i+1) + '">' + dict["books.book_" + (i+1)] + '</option>');
            }
            
            // First and Last appearance books
            $("#item_start_book").val(
                    session_settings["search_start_book"] ? 
                    session_settings["search_start_book"] : -1);
            $("#item_end_book").val(
                    session_settings["search_end_book"] ? 
                    session_settings["search_end_book"] : -1);

            // On change for the different select boxes
            $("#item_start_book").change();
            $("#item_end_book").change();
        }
    });
}

function insertStartChapters() {
    // Get the selected book and its amount of chapters
    var book = $("#item_start_book option:selected");
    var num_chapters = book.data("numChapters");
    
    // Insert all the chapters
    $("#item_start_chap").empty();
    $("#item_start_chap").append('<option selected disabled value="-1">' + dict["books.chapter"] + '</option>');
    for (var i = 0; i < num_chapters; i++) {
        $("#item_start_chap").append('<option value="' + (i+1) + '">' + (i+1) + '</option>');
    }
    
    // First appearance chapters
    if (firstChapterStart) {
        // Setting back the selected chapter from the session
        $("#item_start_chap").val(
                session_settings["search_start_chap"] ? 
                session_settings["search_start_chap"] : -1);
                
        firstChapterStart = false;
    } else {
        // When changing books, preset it to the first chapter
        $("#item_start_chap").val(1);
        
        // Skip it for the first appearance, otherwise it will overwrite the
        // session settings for the last appearance before we had a chance to set it
        $("#item_start_chap").change();
    
        // Show a little [x] to remove the filter
        $("#item_start_label a").remove()
        $("#item_start_label").append('<a tabindex=0 onclick="removeStartFilter()" data-toggle="tooltip" data-placement="top" title="' + dict["search.remove_filter"] + '">[x]</a>')
    }
}

function insertEndChapters() {
    // Get the selected book and its amount of chapters
    var book = $("#item_end_book option:selected");
    var num_chapters = book.data("numChapters");
    
    // Insert all the chapters
    $("#item_end_chap").empty();
    $("#item_end_chap").append('<option selected disabled value="-1">' + dict["books.chapter"] + '</option>');
    for (var i = 0; i < num_chapters; i++) {
        $("#item_end_chap").append('<option value="' + (i+1) + '">' + (i+1) + '</option>');
    }
    
    // Last appearance chapters
    if (firstChapterEnd) {
        // Setting back the selected chapter from the session
        $("#item_end_chap").val(
                session_settings["search_end_chap"] ? 
                session_settings["search_end_chap"] : -1);
                
        firstChapterEnd = false;
    } else {
        // When changing books, preset it to the last chapter
        $("#item_end_chap").val(num_chapters);
    
        // Show a little [x] to remove the filter
        $("#item_end_label a").remove()
        $("#item_end_label").append('<a tabindex=0 onclick="removeEndFilter()" data-toggle="tooltip" data-placement="top" title="' + dict["search.remove_filter"] + '">[x]</a>')
    }
    
    // Activate the onChange event
    $("#item_end_chap").change();
}

function removeStartFilter() {
    // Reset the book and chapter
    $("#item_start_book").val(-1);
    $("#item_start_chap").val(-1);
    
    // Remove the [x]
    $("#item_start_label a").remove()
    
    // Search again
    searchItems();
}

function removeEndFilter() {
    // Reset the book and chapter
    $("#item_end_book").val(-1);
    $("#item_end_chap").val(-1);
    
    // Remove the [x]
    $("#item_end_label a").remove();
    
    // Search again
    searchItems();
}

function removeSpecificFilter() {
    
}


/** Insert the search term from the session */
function insertSearch() {
    
    // Search strings
    $("#item_name").val(
            session_settings["search_name"] ? 
            session_settings["search_name"] : "");
    $("#item_meaning_name").val(
            session_settings["search_meaning_name"] ? 
            session_settings["search_meaning_name"] : "");
    $("#item_descr").val(
            session_settings["item_descr"] ? 
            session_settings["item_descr"] : "");
    
            
    // Dropdown for specific stuff
    $("#item_specific").val(
            session_settings["item_specific"] ? 
            session_settings["item_specific"] : -1);
}

function insertResults() {
    // Get the data of the books, events, peoples, locations & specials 
    // using the search terms
    getBooks(null, {
        "columns": getSearchTerms("books").columns,
        "filters": getSearchTerms("books").filters
    }).then(function(result) { insertItems("books", result); });

    getEvents(null, {
        "columns": getSearchTerms("events").columns,
        "filters": getSearchTerms("events").filters
    }).then(function(result) { insertItems("events", result); });
    
    getPeoples(null, {
        "columns": getSearchTerms("peoples").columns,
        "filters": getSearchTerms("peoples").filters
    }).then(function(result) { insertItems("peoples", result); });
    
    getLocations(null, {
        "columns": getSearchTerms("locations").columns,
        "filters": getSearchTerms("locations").filters
    }).then(function(result) { insertItems("locations", result); });
    
    getSpecials(null, {
        "columns": getSearchTerms("specials").columns,
        "filters": getSearchTerms("specials").filters
    }).then(function(result) { insertItems("specials", result); });
}

/** Get all the filters in API compatible format */
function getFilters() {
    // Get all the search terms, and use them to filter out results
    var name =          session_settings["search_name"] ? 
            "name % " + session_settings["search_name"] : "";
    var meaning_name =          session_settings["search_meaning_name"] ? 
            "meaning_name % " + session_settings["search_meaning_name"] : "";
    var descr =          session_settings["search_descr"] ? 
            "descr % " + session_settings["search_descr"] : "";
            
    // First appearance
    var start_book =              session_settings["search_start_book"] ? 
            "book_start_id >= " + session_settings["search_start_book"] : "";
    var start_chap =                session_settings["search_start_chap"] ? 
            "book_start_chap >= " + session_settings["search_start_chap"] : "";
    
    // Last appearance
    var end_book =              session_settings["search_end_book"] ? 
            "book_end_id <= " + session_settings["search_end_book"] : "";
    var end_chap =                session_settings["search_end_chap"] ? 
            "book_end_chap <= " + session_settings["search_end_chap"] : "";
            
    // First & Last appearance
    var book_ids = "";
    if (session_settings["search_start_book"] && 
        session_settings["search_end_book"]) {
        var book_ids = "id <> " + 
                session_settings["search_start_book"] + "-" + 
                session_settings["search_end_book"];
    } else if (session_settings["search_start_book"]) {
        var book_ids =     session_settings["search_start_book"] ? 
                "id >= " + session_settings["search_start_book"] : "";
    } else if (session_settings["search_end_book"]) {
        var book_ids =     session_settings["search_end_book"] ? 
                "id <= " + session_settings["search_end_book"] : "";
    }
            
    return {
        "name": name,
        "meaning_name": meaning_name,
        "descr": descr,
        "book_ids": book_ids,
        "start_book": start_book,
        "start_chap": start_chap,
        "end_book": end_book,
        "end_chap": end_chap,
    };
}

/** Get the columns and filters to send to the API */
function getSearchTerms(type) {
    var search_terms = {};
    var extra_columns = [];
    
    var filter = getFilters();
    
    switch(type) {
        case "books":
            extra_columns = ["num_chapters"];
            search_terms["name"] = filter.name;
            search_terms["id"] = filter.book_ids;
            break;
            
        case "events":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
            
        case "peoples":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
            
        case "locations":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
            
        case "specials":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
    }
    
    // Filter out anything that isn't filled
    for (key in search_terms) {
        if (search_terms[key] === "") {
            delete search_terms[key];
        }
    }
    
    return {
        "columns": extra_columns.concat(
                    Object.keys(search_terms)
                ).join(", "),
        "filters": Object.values(search_terms).join(", ")
    };
}

/** Updating the session settings and performing the search */
function searchItems() {
    // The search termd inserted
    var name = $("#item_name").val();
    var meaning_name = $("#item_meaning_name").val();
    var descr = $("#item_descr").val();
    var start_book = $("#item_start_book").val();
    var start_chap = $("#item_start_chap").val();
    var end_book = $("#item_end_book").val();
    var end_chap = $("#item_end_chap").val();
    var specific = $("#item_specific").val();
    
    // Update the query to the session
    updateSession({
        "search_name": name,
        "search_meaning_name": meaning_name,
        "search_descr": descr,
        "search_start_book": start_book,
        "search_start_chap": start_chap,
        "search_end_book": end_book,
        "search_end_chap": end_chap,
        "search_specific": specific,
    });
    
    // Recalculate the search results
    insertResults();
}

/** Inserting the results in a readable table format */
function insertItems(type, result) {
    // Start out clean
    $("#tab" + type).empty();
    
    // No errors and at least 1 item of data
    if ((result.error == null) && result.data && result.data.length > 0) {
        
        // Table header is the name
        var table_header = insertHeader(type, "name");
        table_header += insertHeader(type, "meaning_name");
        table_header += insertHeader(type, "descr");
        table_header += insertHeader(type, "book_start");
        table_header += insertHeader(type, "book_end");
        table_header += insertHeader(type, "num_chapters");
        table_header += insertHeader(type, "link");
        
        table_row = [];
        for (var i = 0; i < result.data.length; i++) {
            var data = result.data[i];
            
            // Table header is the name
            table_data = insertData(type, "name", data);
            table_data += insertData(type, "meaning_name", data);
            table_data += insertData(type, "descr", data);
            table_data += insertData(type, "book_start", data);
            table_data += insertData(type, "book_end", data);
            table_data += insertData(type, "num_chapters", data);
            table_data += insertData(type, "link", data);
            
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

/**
 * Inserting a header into the table of results
 * */
function insertHeader(type, name) {
    var types = getTypes(name);
    
    var table_header = "";
    if (types.includes(type)) {
        table_header = '<th scope="col">' + dict["items." + name] + '</th>';
    }
    
    return table_header;
}

/**
 * Inserting data into the table of results
 * */
function insertData(type, name, data) {
    var types = getTypes(name);
    
    var table_data = "";
    if (types.includes(type) && (name == "name")) {
        table_data = '<th scope="row">' + data[name] + '</th>';
    } else if (types.includes(type) && (name == "link")) {
        table_data = '<td>' + getLinkToItem(type, data.id, "self") + '</td>';
    } else if (types.includes(type) && (name == "book_start")) {
        table_data = '<td>' + 
                dict["books.book_" + data["book_start_id"]] + 
                " " + data["book_start_chap"] + 
                ":" + data["book_start_vers"] + 
            '</td>';
    } else if (types.includes(type) && (name == "book_end")) {
        table_data = '<td>' + 
                dict["books.book_" + data["book_end_id"]] + 
                " " + data["book_end_chap"] + 
                ":" + data["book_end_vers"] + 
            '</td>';
    } else if (types.includes(type)) {
        table_data = '<td>' + data[name] + '</td>';
    }
    
    return table_data;
}

function getTypes(name) {
    var types = []
    if (session_settings["search_" + name]) {
        // If this value saved in the session?
        switch(name) {
            case "meaning_name":
                types = ["peoples", "locations", "specials"];
                break;

            case "descr":
                types = ["events", "peoples", "locations", "specials"];
                break;
        }
    } else {
        switch(name) {
            case "name":
            case "link":
                types = ["books", "events", "peoples", "locations", "specials"]
                break;
                
            case "book_start":
            case "book_end":
                types = ["events", "peoples", "locations", "specials"];
                break;
                
            case "num_chapters":
                types = ["books"];
                break;
        }
    }
    
    return types;
}
    