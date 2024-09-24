
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
    }
});