
/** 
 * This function generates the sidebar for an items page
 * Once it is done with the basic layout, it will insert
 * the search term (if already entered), the list of sorts,
 * the list of items and the pagination.
 * 
 * Selected sorting, pagination and items will be styled with class 'active'
 * */
function getItemsMenu() {
    var menu = $("<div>").addClass("col-md-4 col-lg-3").append(`
        <!-- Search bar and sorting -->
        <div class="row mb-2">
            <div class="col-8">
                <div class="input-group w-100">
                    <input type="text" class="form-control" id="item_search" placeholder="Search" onkeyup="searchItems()">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="searchItems()">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
    
            <div class="col-4">
                <div class="btn-group w-100">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Order </button>
                    <div class="dropdown-menu" id="item_sort"> 
                        <!-- We'll get the list as soon as we know the sort -->
                    </div>
                </div>
            </div>
        </div>
    
        <!-- The list of items -->
        <div class="row">
            <div class="col-md-12">
                <div class="list-group text-center" id="item_list">
                    <!-- We'll get the list as soon as we know the page and sort -->
                </div>
            </div>
        </div>
    
        <!-- Pagination -->
        <div class="row mt-2" id="item_pagination">
            <div class="col-md-12">
                <ul class="pagination mt-2 mb-2" id="item_pages">
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

function getContentDiv() {
    return $("<div>")
                    .addClass("col-md-8 col-lg-9")
                    .attr("id", "item_content")
}

/** 
 * This function generates the right side of the page with either an
 * explanation on how to navigate with the sidebar, or information on
 * the selected item from the sidebar. 
 * 
 * The information displayed, is generated per item type. */
function getItemsContent() {
    
    if (get_settings["id"]) {
        switch(page_id) {
            case "books":
                // How the data of the current item should be displayed
                var getItemContent = getBookContent;
                break;
                
            case "peoples":
                // How the data of the current item should be displayed
                var getItemContent = getPeopleContent;
                break;
        }
        
        // Get the data of the current item
        getData(page_id, get_settings["id"]).then(getItemContent)
    } else {
        // No item has been selected, show default information
        var content = $("#item_content").append(`
            <div class="row mb-5 pb-5 text-center">
                <div class="col-lg-11 px-lg-5 px-md-3">
                    <h1 class="mb-3">` + toUpperCaseFirst(page_id) + `</h1>
                    <p class="lead">Then, my friend, when darkness overspreads my eyes, and heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing, Oh, would I could describe these conceptions, could impress upon paper all that is living so full and warm within me, that it might be the mirror of my soul, as my soul is the mirror of the infinite God!&nbsp;</p>
                </div>
            </div>
        `);
    }
}

/**
 * Inserts all the things that need the basic layout to be ready.
 * It needs to append stuff to elements with certain IDs from the basic layout
 * and these will not be available until it is ready.
 * */
function insertAll() {
    // Insert the search term from the session
    insertSearch();
    
    // Insert the sorting types and activate the sort from the session
    insertSorts();
    
    // Insert the list of items and activate the id from the session
    insertItems();
    
    // Insert pagination and activate the page from the session
    insertPages();
}

/** Insert the search term from the session */
function insertSearch() {
    $("#item_search").val(
            session_settings["search"] ? 
            session_settings["search"] : "");
}

/** Insert the sorting types and activate the sort from the session */
function insertSorts() {
                      
    // Start out clean
    $("#item_sort").empty();
    
    // Get the settings
    var currentSort = session_settings["sort"] ? 
                      session_settings["sort"] : "";

    // The current sort is compare to the different sorting types
    // Only the corresponding sorting type will have the value set to 'true'
    var sort_0_to_9 = currentSort == "0_to_9";
    var sort_9_to_0 = currentSort == "9_to_0";
    var sort_a_to_z = currentSort == "a_to_z";
    var sort_z_to_a = currentSort == "z_to_a";
    
    // Insert the sorts and add the 'active' class 
    // if the corresponing sort is active
    $("#item_sort").append('<a class="dropdown-item' + 
            (sort_0_to_9 ? " active" : "") + '" onclick="setSort0to9()"> ' +
            'Bible order (ascending)' + ' </a>');
    $("#item_sort").append('<a class="dropdown-item' + 
            (sort_9_to_0 ? " active" : "") + '" onclick="setSort9to0()"> ' + 
            'Bible order (descending)' + ' </a>');
    $("#item_sort").append('<a class="dropdown-item' + 
            (sort_a_to_z ? " active" : "") + '" onclick="setSortAtoZ()"> ' + 
            'Alphabetic (ascending)' + ' </a>');
    $("#item_sort").append('<a class="dropdown-item' + 
            (sort_z_to_a ? " active" : "") + '" onclick="setSortZtoA()"> ' + 
            'Alphabetic (descending)' + ' </a>');
}

/** Insert the list of items and activate the id from the session */
function insertItems() {
    
    // Get the settings
    var currentPage = session_settings["page"] ? 
                      session_settings["page"] : 0;
    var currentSort = setSort(session_settings["sort"] ? 
                              session_settings["sort"] : "");
    var currentSearch = session_settings["search"] ? 
            "name % " + session_settings["search"] : "";

    // Get a page of items. A page is 10 items long
    // Sort by the chosen sorting method
    // Filter by the search term    
    getData(page_id, null, {
        "limit": pageSize,
        "offset": currentPage*pageSize,
        "sort": currentSort,
        "filters": currentSearch
    }).then(function (result) {
        // Start out clean
        $("#item_list").empty();

        // No errors and at least 1 item of data
        if ((result.error == null) && result.data && result.data.length > 0) {
            
            // Some variables that depend on the page id
            // These are the name of the id column and the link to an item
            var page_id_single = page_id.substr(0, page_id.length - 1);
            var item_id = page_id_single + "_id";
            var item_link = "/" + page_id + "/" + page_id_single + "/";

            // Insert all the items of a page
            for (var i = 0; i < pageSize; i++) {
                if (i < result.data.length) {
                    // Per item
                    var item_obj = result.data[i];
                    
                    // Add the active class if the current id 
                    // corresponds with the selected id
                    var is_active = item_obj[item_id] == get_settings["id"];
                    var active = is_active ? " active" : "";
                    
                    // Adding the item to the current page
                    $("#item_list").append(
                            '<a href="' + 
                                // Link to the selected item
                                setParameters(item_link + item_obj[item_id]) + 
                                // Classes
                                '" class="list-group-item list-group-item-action' + active + '"> ' + 
                                // Name of the item
                                item_obj["name"] + 
                            ' </a>');
                } else {
                    // Fill up the rest with invisible empty items
                    $("#item_list").append('<a class="list-group-item list-group-item-action invisible"> empty </a>');
                }
            }
        } else {
            // TODO:
            // Error melding geven dat database niet bereikt kan worden
            $("#item_list").append(result.error ? result.error : "No results found");
        }
    });
}

/** Insert pagination and activate the page from the session */
function insertPages() {   
    
    // Get the settings
    var currentPage = session_settings["page"] ? 
                      session_settings["page"] : 0;
    var currentSort = setSort(session_settings["sort"] ? 
                              session_settings["sort"] : "");
    var currentSearch = session_settings["search"] ? 
            "name % " + session_settings["search"] : ""; 

    // Count the amount of items
    // Sort by the chosen sorting method
    // Filter by the search term
    getData(page_id, null, {
        "sort": currentSort,
        "filters": currentSearch,
        "calculations": "count"
    }).then(function (result) {

        // No errors and at least 1 item of data
        if ((result.error == null) && result.data && result.data.length > 0) {
            // Start out clean
            $("#item_pages").empty();

            // Count the amount of pages
            var count = Math.ceil(result.data[0].count / pageSize);

            if (count <= 9) {
                // Insert all the pages
                for (var i = 0; i < count; i++) {
                    insertPage(i);
                }
            } else {
                var currentPageInt = parseInt(currentPage, 10);
                
                // We have more than 9 pages, so we need to know the active page
                // and insert pages accordingly
                if (currentPageInt < 5) {
                    // Insert the first 7 pages
                    for (var i = 0; i < 7; i++) {
                        insertPage(i);
                    }
                    
                    // This is a divider, but also an option to select a page
                    insertDivider();
                
                    // The last page
                    insertPage(count - 1);
                } else if (currentPageInt > (count - 6)) {
                    // The first page
                    insertPage(0)
                    
                    // This is a divider, but also an option to select a page
                    insertDivider();
                    
                    // Insert the last 7 pages
                    for (var i = count - 7; i < count; i++) {
                        insertPage(i)
                    }
                } else {
                    // The first page
                    insertPage(0)
                    
                    // This is a divider, but also an option to select a page
                    insertDivider();
                    
                    // Insert all the pages
                    for (var i = currentPageInt - 2; i < currentPageInt + 3; i++) {
                        insertPage(i)
                    }
                    
                    // This is a divider, but also an option to select a page
                    insertDivider();
                
                    // The last page
                    insertPage(count - 1);
                }
            }

            if (count > 1) {
                // pagination
                $("#item_pagination").css("display", "");
            } else {
                // No pagination
                $("#item_pagination").css("display", "none");
            }
        } else {
            // No pagination
            $("#item_pagination").css("display", "none");
        }
    });
}

function searchItems() {
    // The search term inserted
    var query = $("#item_search").val();
    
    // Update the query to the session
    updateSession({
        "search": query,
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertItems();
    insertPages();
    
}

function setSort0to9() {
    updateSession({
        "sort": "0_to_9", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertItems();
    insertPages();
}

function setSort9to0() {
    updateSession({
        "sort": "9_to_0", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertItems();
    insertPages();
}

function setSortAtoZ() {
    updateSession({
        "sort": "a_to_z", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertItems();
    insertPages();
}

function setSortZtoA() {
    updateSession({
        "sort": "z_to_a", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertItems();
    insertPages();
}

function setSort(sort="") {
    if (sort == "") {
        // Default sort
        sort = "0_to_9";
    }
    
    switch(page_id) {
        case "books":
            if (sort == "0_to_9") { sort = "order_id asc"; }
            if (sort == "9_to_0") { sort = "order_id desc"; }
            if (sort == "a_to_z") { sort = "name asc"; }
            if (sort == "z_to_a") { sort = "name desc"; }
            break;
            
        default:
            if (sort == "0_to_9") { sort = "order_id asc"; }
            if (sort == "9_to_0") { sort = "order_id desc"; }
            if (sort == "a_to_z") { sort = "name asc"; }
            if (sort == "z_to_a") { sort = "name desc"; }
            break;
    }
    
    return sort
}

function setPage(page) {
    updateSession({"page": page});
    
    // Recalculate the booklist and pagination
    insertItems();
    insertPages();
}

function insertPage(page) {
    // Currently selected page
    var currentPage = session_settings["page"] ? 
                      session_settings["page"] : 0;
                      
    // Are we inserting the currently selected page?
    var active = (page == currentPage) ? "active" : "";
    
    // Insert the page
    $("#item_pages").append(`
        <li class="page-item font-weight-bold w-100 ` + active + `"> 
            <a class="page-link text-center" onclick="setPage(` + page + `)">` + (page + 1) + `</a> 
        </li>
    `);
}

function insertDivider() {
    $("#item_pages").append(`
        <li class="page-item font-weight-bold w-100 disabled"> 
            <a class="page-link text-center" onclick="">...</a> 
        </li>
    `);
}