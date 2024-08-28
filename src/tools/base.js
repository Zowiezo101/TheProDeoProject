
function onMenuToggle() {
    
    var button = $("#toggle_menu");
    if (button.hasClass("show_menu")) {
        // Hide the menu, make sure the window doesn't shift by 
        // specifically setting the height
        $("#content_col").css("height", $("#item_bar").css("height"));
        if ($("#small_screen").length > 0) {
            $("#item_bar").removeClass("d-md-block");
        } else {
            $("#item_bar").addClass("d-none");
        }
        
        // Update the button
        button.addClass("hide_menu");
        button.removeClass("show_menu");
        button.html('<i class="fa fa-angle-double-right" aria-hidden="true"></i>');
    } else if (button.hasClass("hide_menu")) {
        // Show the menu
        if ($("#small_screen").length > 0) {
            $("#item_bar").addClass("d-md-block");
        } else {
            $("#item_bar").removeClass("d-none");
        }
        
        // Update the button
        button.addClass("show_menu");
        button.removeClass("hide_menu");
        button.html('<i class="fa fa-angle-double-left" aria-hidden="true"></i>');
    }
    
    // Toggle a window resize event
    $(window).trigger('resize');
    
    return;
}

function onSortUpdate() {
    // Get the selected sort
    var sort = $(event.target).attr("id");

    // Update the session settings
    updateSession({
        "sort": sort
    });
    
    setSort(sort);
}

function setSort(sort) {
    // Highlight the selected sorting method in the list
    $("#item_sort .active").removeClass("active");
    $("#" + sort).addClass("active");
    
    // The pagination table
    var table = $("#item_list").DataTable();
    
    // Set the sorting method
    switch(sort) {
        case "0_to_9":
            table.column(1).order('asc').draw();
            break;
        case "9_to_0":
            table.column(1).order('desc').draw();
            break;
        case "a_to_z":
            table.column(0).order('asc').draw();
            break;
        case "z_to_a":
            table.column(0).order('desc').draw();
            break;
    }
    
    // Go back to the first page
    setPage(0);
}

function onFirstPage() {    
    // Set the correct page number
    setPage('first');
}

function onPrevPage() {
    // Set the correct page number
    setPage('previous');
}

function onCustomPage() {
    // Custom page value
    var page = $("#curr_page").val();
    
    page = parseInt(page, 10) - 1;
    
    // Set the correct page number
    setPage(page);
}

function onNextPage() {
    // Set the correct page number
    setPage('next');
}

function onLastPage() {
    // Set the correct page number
    setPage('last');
}

function setPage(page) {
    // The pagination table
    var table = $("#item_list").DataTable();
    
    // The total amount of pages
    var num_pages = table.page.info().pages;
    
    $("#num_pages").text("/ " + num_pages);
    
    if (Number.isInteger(page)) {
        // Make sure we stay inside the bounds
        page = Math.max(0, page);
        page = Math.min(num_pages - 1, page);
    }
    
    // Show the selected page in the table
    table.page(page).draw("page");
    
    // Retrieve the actual page number (in case of first, prev, etc)
    page = table.page();

    // Update the session settings
    updateSession({
        "page": page
    });
    
    if (num_pages > 1) {
        // Enable these buttons again
        $("#first_page").parent().removeClass("invisible");
        $("#prev_page").parent().removeClass("invisible");
        $("#next_page").parent().removeClass("invisible");
        $("#last_page").parent().removeClass("invisible");
        
        // Enable/disable to proper buttons
        switch(page) {
            case 0:
                // Disable the first and prev buttons
                $("#first_page").parent().addClass("invisible");
                $("#prev_page").parent().addClass("invisible");
                break;

            case num_pages - 1:
                // Disable the last and next buttons
                $("#next_page").parent().addClass("invisible");
                $("#last_page").parent().addClass("invisible");
                break;
        }

        $("#curr_page").val(page + 1);
                
        // There is more than one page, make the pagination visible
        $("#item_pagination").removeClass("invisible");
    } else {
        // There is only one page, make the pagination invisible
        $("#item_pagination").addClass("invisible");
    }
}

function onSearchUpdate() {
    // Get the selected search term
    var search = $("#item_search").val();
    
    // Update the session settings
    updateSession({
        "search": search
    });
    
    setSearch(search);
}

function setSearch(search) {    
    // The pagination table
    table = $("#item_list").DataTable();
    
    // Search for the following term
    table.column(0).search(search);
    
    // Go back to the first page
    setPage(0);
}
    
//function updatePage() {
//    // This happens when a search is done
//    // For example, a new page is loaded, the sort is changed or
//    // a search term is entered
//    
//    // The base URL is stored in the body of the page
//    var base_url = $("body").attr("data-base-url");
//    
//    // Get the selected search term
//    var search = $("#item_search").val();
//    
//    // Get the selected sort
//    var sort = $("#item_sort .active").attr("id");
//    
//    // Get the current page
//    var page = parseInt($("#curr_page").val(), 10) - 1;
//    
//    // Get the page with the current settings
//    var type = $("#item_list").attr("data-page-type");
//    getPage(type, page, {
//        "sort": sort,
//        "search": search
//    }).then(function (data) {
//        // Start out clean
//        $("#item_list").empty();
//        
//        // Update the page and select the correct item
//        if (data.hasOwnProperty("error") && data.error !== "") {
//            // Show an error if applicable
//            $("#item_list").append(data.error);
//        } else if (data.hasOwnProperty("records") && data.records.length === 0) {
//            // Show that there are no results
//            $("#item_list").append(dict["database.no_results"]);
//        } else {
//            // Some variables
//            var page_size = parseInt($("#item_list").attr("data-page-size"), 10);
//            var page_url = $("#item_list").attr("data-page-url");
//            var curr_id = $("#item_list").attr("data-id");
//            
//            // Fill up the page
//            for (var i = 0; i < page_size; i++) {
//                var item = data.records[i];
//                
//                // We want a full page of items inserted. 
//                // If there aren't enough items, fill the rest up with blanks
//                var option = '<a class="list-group-item list-group-item-action invisible"> empty </a>';
//                if (i < data.records.length) {
//                    // The link to refer to
//                    var href = base_url + page_url + "/" + item.id;
//                    
//                    // If an option in the sidebar is selected, it needs to be highlighted
//                    var classes = "list-group-item list-group-item-action";
//                    if (curr_id === item.id) {
//                        classes = classes + " active";
//                    }
//                    
//                    // The name to be shown in the sidebar
//                    var value = item.name;
//                    if (value === "timeline.global") {
//                        // In case of the timeline, there is a global timeline
//                        // consisting of all the events
//                        $alue = dict[value];
//                    }
//                    
//                    if (item.hasOwnProperty("aka") && item.aka !== "") {
//                        // The AKA value is only given when searching for a name and there is a hit
//                        // with an AKA value.
//                        value = value + " (" + item.aka + ")";
//                    }
//            
//                    if ($("#loading_screen").length > 0) {
//                        var onclick = "showLoadingScreen()";
//                        if (type === "worldmap") {
//                            onclick = "getLinkToMap(" + item.id + ")";
//                            href = "javascript: void(0)";
//                        }
//                    } else {
//                        var onclick = "";
//                    }
//                    
//                    var option = '<a href="' + href + '" class="' + classes + '" onclick="' + onclick + '">' + value + '</a>';
//                }
//                
//                $("#item_list").append(option);
//            }
//        }
//        
//        total_num_pages = 0;
//        if (data.hasOwnProperty("paging") && data.paging !== "") {
//            total_num_pages = parseInt(data.paging, 10);
//        }
//        
//        // Make the pagination visible/invisible
//        if (total_num_pages > 1) {
//            // pagination
//            $("#item_pagination").addClass("visible");
//            $("#item_pagination").removeClass("invisible");
//            
//            // Show the amount of pages
//            $("#num_pages").text(total_num_pages);
//        } else {
//            // No pagination
//            $("#item_pagination").addClass("invisible");
//            $("#item_pagination").removeClass("visible");
//        }
//        
//    });
//}

function onFilter() {
    
}

function onFilterUpdate() {
    
}
    
function showLoadingScreen() {
    // Make the loading screen visible and the item content invisible
    $("#loading_screen").removeClass("d-none");
    $("#loading_screen").fadeIn();
    $("#item_content").addClass("invisible");
    
}

function hideLoadingScreen() {
    $("#item_content").removeClass("invisible");
    $("#loading_screen").fadeOut();
}

$(function() {
    // Scroll to the data for smaller screens
    if ($("#item_content").length > 0) {
        
        if (window.innerWidth < 768) {
            $("html, body").animate({
                scrollTop: $("#item_content").offset().top
            }, 2000);
        }
    
        // Set the dataTable to be able to sort and change pages
        table = $("#item_list").DataTable({
            
            layout: {
                topStart: null,
                topEnd: null,
                bottomStart: null,
                bottomEnd: null
            },
            
            columns: [
                {
                    name: "name"
                },
                {
                    name: "order_id",
                    visible: false
                }
            ],
            order: [1, "asc"]
        });
        
        
        // Setting the sorting type
        if ($("#item_list").data("table-sort")) {
            setSort($("#item_list").data("table-sort"));
        }
        
        // Adding the saved search term
        if ($("#item_list").data("table-search")) {
            setSearch($("#item_list").data("table-search"));
        }
        
        // Setting the saved page
        if ($("#item_list").data("table-page")) {
            setPage($("#item_list").data("table-page"));
        }
    }
});