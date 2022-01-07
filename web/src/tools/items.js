
/** 
 * This function generates the sidebar for an items page
 * Once it is done with the basic layout, it will insert
 * the search term (if already entered), the list of sorts,
 * the list of items and the pagination.
 * 
 * Selected sorting, pagination and items will be styled with class 'active'
 * */
var pageCount = 0;
var focusPage = false;

function getItemsMenu() {
    var menu = $("<div>").addClass("col-md-4 col-lg-3").append(`
        <!-- Search bar and sorting -->
        <div class="row mb-2">
            <div class="col-8">
                <div class="input-group w-100">
                    <input type="text" class="form-control" id="item_search" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="searchItems()">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
    
            <div class="col-4">
                <div class="btn-group w-100">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> ` + dict["database.order"] + ` </button>
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
                <ul class="pagination mt-2 mb-2 justify-content-center" id="item_pages">
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
        // How the data of the current item should be displayed
        switch(page_id) {
            case "books":
                var getItemContent = getBookContent;
                break;
                
            case "events": 
                var getItemContent = getEventContent;
                break;
                
            case "peoples":
                var getItemContent = getPeopleContent;
                break;
                
            case "locations": 
                var getItemContent = getLocationContent;
                break;
                
            case "specials": 
                var getItemContent = getSpecialContent;
                break;
                
            case "familytree":
                var getItemContent = getFamilytreeContent;
                break;
                
            case "familytree":
                var getItemContent = getTimelineContent;
                break;
        }
        
        // Get the data of the current item
        getItem(page_id, get_settings["id"]).then(getItemContent)
    } else {
        // No item has been selected, show default information
        var content = $("#item_content").append(`
            <div class="row mb-5 pb-5 text-center">
                <div class="col-lg-11 px-lg-5 px-md-3">
                    <h1 class="mb-3">` + dict["navigation." + page_id] + `</h1>
                    <p class="lead">` + dict[page_id + ".overview"] + `.</p>
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
            dict["order.0_to_9"] + ' </a>');
    $("#item_sort").append('<a class="dropdown-item' + 
            (sort_9_to_0 ? " active" : "") + '" onclick="setSort9to0()"> ' + 
            dict["order.9_to_0"] + ' </a>');
    $("#item_sort").append('<a class="dropdown-item' + 
            (sort_a_to_z ? " active" : "") + '" onclick="setSortAtoZ()"> ' + 
            dict["order.a_to_z"] + ' </a>');
    $("#item_sort").append('<a class="dropdown-item' + 
            (sort_z_to_a ? " active" : "") + '" onclick="setSortZtoA()"> ' + 
            dict["order.z_to_a"] + ' </a>');
}

/** Insert the list of items and activate the id from the session */
function insertPages() {
    
    // Get the settings
    var currentPage = session_settings["page"] ? 
                      session_settings["page"] : 0;
    var currentSort = session_settings["sort"] ? 
                      session_settings["sort"] : "";
    var currentSearch = session_settings["search"] ? 
                        session_settings["search"] : "";

    // Get a page of items. A page is 10 items long
    // Sort by the chosen sorting method
    // Filter by the search term    
    getItemPage(page_id,
        currentPage,
        currentSort,
        currentSearch,
    ).then(function (result) {
        // Start out clean
        $("#item_list").empty();
        $("#item_pages").empty();

        // We got results
        if (result.records) {

            // Insert all the items of a page
            for (var i = 0; i < pageSize; i++) {
                if (i < result.records.length) {
                    // Per item
                    var item_obj = result.records[i];
                    
                    // Add the active class if the current id 
                    // corresponds with the selected id
                    var is_active = item_obj.id == get_settings["id"];
                    var active = is_active ? " active" : "";
                    
                    // Adding the item to the current page
                    $("#item_list").append(
                        getLinkToItem(
                            page_id, 
                            item_obj.id, 
                            item_obj["name"], 
                            'list-group-item list-group-item-action' + active)
                    );
                } else {
                    // Fill up the rest with invisible empty items
                    $("#item_list").append('<a class="list-group-item list-group-item-action invisible"> empty </a>');
                }
            }
            
            // Insert the paging
            pageCount = result.paging;
            
            // Pagination
            insertFirstPage();
            insertPrevPage();
            insertPage();
            insertNextPage();
            insertLastPage();

            if (pageCount > 1) {
                // pagination
                $("#item_pagination").css("display", "");
            } else {
                // No pagination
                $("#item_pagination").css("display", "none");
            }
        } else {
            // TODO:
            // Error melding geven dat database niet bereikt kan worden
            $("#item_list").append(result.error ? result.error : "No results found");
            
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
    insertPages();
    
}

function setSort0to9() {
    updateSession({
        "sort": "0_to_9", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertPages();
}

function setSort9to0() {
    updateSession({
        "sort": "9_to_0", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertPages();
}

function setSortAtoZ() {
    updateSession({
        "sort": "a_to_z", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertPages();
}

function setSortZtoA() {
    updateSession({
        "sort": "z_to_a", 
        "page": null
    });
    
    // Recalculate the itemlist and pagination
    insertSorts();
    insertPages();
}

function setPage(page) {
    if ((page >= 0) && (page < pageCount)) {
        updateSession({"page": page});

        // Recalculate the booklist and pagination
        insertPages();
    }
}

function insertFirstPage() {
    // Currently selected page
    var currentPage = parseInt(session_settings["page"] ? 
                               session_settings["page"] : 0, 10);
    var disabled = currentPage == 0 ? "disabled" : "";
                               
    // Insert the button to go the the first page
    $("#item_pages").append(`
        <li class="page-item font-weight-bold ` + disabled + `" ` + disabled + `>
            <a class="page-link" onclick="setPage(` + 0 + `)">
                <span class="text-primary">` + dict["database.first"] + `</span>
            </a>
        </li>
    `);
}

function insertPrevPage() {
    // Currently selected page
    var currentPage = parseInt(session_settings["page"] ? 
                               session_settings["page"] : 0, 10);
    var disabled = currentPage == 0 ? "disabled" : "";
                      
    // Insert the button to go the the previous page
    $("#item_pages").append(`
        <li class="page-item font-weight-bold ` + disabled + ` mr-1" ` + disabled + `>
            <a class="page-link" onclick="setPage(` + (currentPage - 1) + `)">
                <span class="text-primary">«</span>
            </a>
        </li>
    `);
}

function insertPage() {
    // Currently selected page
    var currentPage = parseInt(session_settings["page"] ? 
                               session_settings["page"] : 0, 10);
                               
    var maxLength = Math.max(pageCount.toString().length, 2);
    var width = 15*maxLength + 15;
    
    // Insert the page
    $("#item_pages").append(`
        <li class="page-item">
            <div class="form-inline">
                <input class="form-control mx-auto" style="width: ` + width + `px;" value="` + (currentPage + 1) + `" type="number" maxlength="` + maxLength + `" id="page_search">
                <label class="mx-1"> ` + dict["database.out_of"] + pageCount + ` </label>
            </div>
        </li>
    `);
    
    // The keyup event for the input box
    $("#page_search").on("keyup", function(event) {
        // The search term inserted
        var query = $("#page_search").val();
        if (/^\d+$/.test(query)) {
            // If it's a number, go to the page
            focusPage = true;
            setPage(parseInt(query, 10) - 1);
        }
    });
        
    if (focusPage == true) {
        $("#page_search").focus();

        // The focus forces the cursor at the beginning of the text
        var val = $("#page_search").val(); //store the value of the element

        $("#page_search").val(""); //clear the value of the element
        $("#page_search").val(val); //set that value back. 

        focusPage = false;
    }
}

function insertNextPage() {
    // Currently selected page
    var currentPage = parseInt(session_settings["page"] ? 
                               session_settings["page"] : 0, 10);
    var disabled = currentPage == (pageCount - 1) ? "disabled" : "";
                      
    // Insert the button to go the the next page
    $("#item_pages").append(`
        <li class="page-item font-weight-bold ` + disabled + ` ml-1" ` + disabled + `>
            <a class="page-link" onclick="setPage(` + (currentPage + 1) + `)">
                <span class="text-primary">»</span>
            </a>
        </li>
    `);
}

function insertLastPage() {
    // Currently selected page
    var currentPage = parseInt(session_settings["page"] ? 
                               session_settings["page"] : 0, 10);
    var disabled = currentPage == (pageCount - 1) ? "disabled" : "";
                               
    // Insert the button to go the the last page
    $("#item_pages").append(`
        <li class="page-item font-weight-bold ` + disabled + `" ` + disabled + `>
            <a class="page-link" onclick="setPage(` + (pageCount - 1) + `)">
                <span class="text-primary">` + dict["database.last"] + `</span>
            </a>
        </li>
    `);
}

function insertDetail(item, detail) {
    if ((detail == "book_start") && (item["book_start_id"])) {
        var book_id = dict["books.book_" + item["book_start_id"]];
        var book_chap = item["book_start_chap"];
        var book_vers = item["book_start_vers"];
        
        item["book_start"] = 
                "<a " + 
                    "href='" + getLink(item["book_start_id"], book_chap, book_vers) + "' " + 
                    "target='_blank' " + 
                    "class='font-weight-bold'>" + 
                    book_id + " " + book_chap + ":" + book_vers + 
                "</a>";
    } else if ((detail == "book_end") && (item["book_end_id"])) {
        var book_id = dict["books.book_" + item["book_end_id"]];
        var book_chap = item["book_end_chap"];
        var book_vers = item["book_end_vers"];
        
        item["book_end"] = 
                "<a " + 
                    "href='" + getLink(item["book_end_id"], book_chap, book_vers) + "' " + 
                    "target='_blank' " + 
                    "class='font-weight-bold'>" + 
                    book_id + " " + book_chap + ":" + book_vers + 
                "</a>";
    } else if (detail == "gender") {
        item[detail] = getGender(item[detail]);
    } else if (detail == "tribe") {
        item[detail] = getTribe(item[detail]);
    } else if (detail == "type" && (page_id == "locations")) {
        item[detail] = getTypeLocation(item[detail]);
    } else if (detail == "type" && (page_id == "specials")) {
        item[detail] = getTypeSpecial(item[detail]);
    }
    
    return item[detail] && (item[detail] != -1) ? 
    `<tr>
        <th scope="row">` + dict["items." + detail] + `</th>
        <td>` + item[detail] + `</td>
    </tr>` : "";
}

function insertDetailLink(item, detail) {
    
    if (item[detail]) {
        var links = [];
        for (var i = 0; i < item[detail].length; i++) {
            var data = item[detail][i];
            switch(detail) {
                case "children":
                case "parents":
                case "daughter":
                case "son":
                    to_table = "peoples";
                    break;

                case "previous":
                case "next":
                    to_table = "events";
                    break;

                default:
                    to_table = detail;
                    break;
            }
            
            if ((page_id == "peoples" && detail == "locations") || 
                    (page_id == "locations" && detail == "peoples")) {
                // These two contain a type as well
                data.name = data.name + getTypeLink(data.type);
            }
            
            // Get the link to the page
            links.push(
                getLinkToItem(
                    to_table, 
                    data.id, 
                    data.name)
            );
        }
        
        if ((detail == page_id) && 
                ((detail == "peoples") || (detail == "locations"))) {
            detail = "aka";
        }
          

        item[detail] = links.join("<br>");
    }
    
    return item[detail] ? `<tr>
        <th scope="row">` + dict["items." + detail] + `</th>
        <td>` + 
            item[detail] + 
        `</td>
    </tr>` : "";
}


function getLink(bookIdx, chapIdx, verseIdx) {

    if (get_settings["lang"] == "nl") {
        // The abbriviation used by the website
        var bookList = ["GEN", "EXO", "LEV", "NUM", "DEU",
                       "JOS", "JDG", "RUT", "1SA", "2SA",
                       "1KI", "2KI", "1CH", "2CH", "EZR",
                       "NEH", "EST", "JOB", "PSA", "PRO",
                       "ECC", "SNG", "ISA", "JER", "LAM",
                       "EZK", "DAN", "HOS", "JOL", "AMO",
                       "OBA", "JON", "MIC", "NAM", "HAB",
                       "ZEP", "HAG", "ZEC", "MAL", "MAT",
                       "MRK", "LUK", "JHN", "ACT", "ROM",
                       "1CO", "2CO", "GAL", "EPH", "PHP",
                       "COL", "1TH", "2TH", "1TI", "2TI",
                       "TIT", "PHM", "HEB", "JAS", "1PE",
                       "2PE", "1JN", "2JN", "3JN", "JUD",
                       "REV"];
    
        // Link to a certain part of the webpage, to get the exact verse mentioned
        var weblink = "https://debijbel.nl/bijbel/NBV/" + bookList[bookIdx - 1] + "." + chapIdx + "." + verseIdx;
    } else {
        // The bookname used by the website
        var bookList = ["Genesis", "Exodus", "Leviticus", "Numbers", "Deuteronomy",
                       "Joshua", "Judges", "Ruth", "1 Samuel", "2 Samuel",
                       "1 Kings", "2 Kings", "1 Chronicles", "2 Chronicles", "Ezra",
                       "Nehemiah", "Esther", "Job", "Psalm", "Proverbs",
                       "Ecclesiastes", "Song of Solomon", "Isaiah", "Jeremiah", "Lamentations",
                       "Ezekiel", "Daniel", "Hosea", "Joel", "Amos",
                       "Obadiah", "Jonah", "Micah", "Nahum", "Habakkuk",
                       "Zephaniah", "Haggai", "Zechariah", "Malachi", "Matthew",
                       "Mark", "Luke", "John", "Acts", "Romans",
                       "1 Corinthians", "2 Corinthians", "Galatians", "Ephesians", "Philippians",
                       "Colossians", "1 Thessalonians", "2 Thessalonians", "1 Timothy", "2 Timothy",
                       "Titus", "Philemon", "Hebrews", "James", "1 Peter",
                       "2 Peter", "1 John", "2 John", "3 John", "Jude",
                       "Revelation",];
                   
        var weblink = "https://www.biblegateway.com/passage/?search=" + bookList[bookIdx - 1] + "+" + chapIdx + ":" + verseIdx + "&version=NLT";
    }

    return weblink;
}