
<script>

function onMenuToggle() {
    
    var button = $("#toggle_menu");
    if (button.hasClass("show_menu")) {
        // Hide the menu, make sure the window doesn't shift by 
        // specifically setting the height
        $("#content_col").css("height", $("#item_bar").css("height"));
<?php if (array_search($page_id, $map)) { ?>
        $("#item_bar").removeClass("d-md-block");
<?php } else { ?>
        $("#item_bar").addClass("d-none");
<?php } ?>
        
        // Update the button
        button.addClass("hide_menu");
        button.removeClass("show_menu");
        button.html('<i class="fa fa-angle-double-right" aria-hidden="true"></i>');
    } else if (button.hasClass("hide_menu")) {
        // Show the menu
<?php if (array_search($page_id, $map)) { ?>
        $("#item_bar").addClass("d-md-block");
<?php } else { ?>
        $("#item_bar").removeClass("d-none");
<?php } ?>
        
        // Update the button
        button.addClass("show_menu");
        button.removeClass("hide_menu");
        button.html('<i class="fa fa-angle-double-left" aria-hidden="true"></i>');
    } else {
        // ????
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
    
    // Highlight the selected sorting method in the list
    $("#item_sort .active").removeClass("active");
    $("#" + sort).addClass("active");
    
    // Start with the first page again (and trigger onPageUpdate)
    $("#curr_page").val(1).trigger("onkeyup");
}

function onPageUpdate() {
    // The amount of pages
    var num_pages = parseInt($("#num_pages").text(), 10) - 1;
    
    // Get the current page
    var page = parseInt($("#curr_page").val(), 10) - 1;
    
    // Only do something if the page is a number
    if (!isNaN(page)) {
    
        // Get the button that was clicked
        var id = event.currentTarget.id;
        switch(id) {
            case "first_page":
                page = 0;
                break;

            case "prev_page":
                page = page - 1;
                break;

            case "next_page":
                page = page + 1;
                break;

            case "last_page":
                page = num_pages;
                break;            
        }

        // Make sure the page is within the limits
        page = Math.min(page, num_pages);    
        page = Math.max(page, 0);

        // Update the session settings
        updateSession({
            "page": page
        });

        // Update the pagination buttons
        if (page === 0) {
            // Disable first and prev buttons
            $("#first_page").parent().addClass("disabled");
            $("#prev_page").parent().addClass("disabled");
            $("#first_page").parent().attr("disabled", true);
            $("#prev_page").parent().attr("disabled", true);
        } else {
            // Enable these buttons again
            $("#first_page").parent().removeClass("disabled");
            $("#prev_page").parent().removeClass("disabled");
            $("#first_page").parent().attr("disabled", false);
            $("#prev_page").parent().attr("disabled", false);
        }

        if (page === num_pages) {
            // Disable last and next buttons
            $("#last_page").parent().addClass("disabled");
            $("#next_page").parent().addClass("disabled");
            $("#last_page").parent().attr("disabled", true);
            $("#next_page").parent().attr("disabled", true);
        } else {
            // Enable these buttons again
            $("#last_page").parent().removeClass("disabled");
            $("#next_page").parent().removeClass("disabled");
            $("#last_page").parent().attr("disabled", false);
            $("#next_page").parent().attr("disabled", false);
        }

        $("#curr_page").val(page + 1);

        // The page is updated with the new information
        updatePage();
    }
}

function onSearch() {
    // Get the selected search term
    var search = $("#item_search").val();
    
    // Update the session settings
    updateSession({
        "search": search
    });
    
    // Start with the first page again (and trigger onPageUpdate)
    $("#curr_page").val(1).trigger("onkeyup");
}
    
function updatePage() {
    // This happens when a search is done
    // For example, a new page is loaded, the sort is changed or
    // a search term is entered
    
    // Get the selected search term
    var search = $("#item_search").val();
    
    // Get the selected sort
    var sort = $("#item_sort .active").attr("id");
    
    // Get the current page
    var page = parseInt($("#curr_page").val(), 10) - 1;
    
    // Get the page with the current settings
    var type = "<?= $type; ?>";
    getPage(type, page, {
        "sort": sort,
        "filter": search
    }).then(function (data) {
        // Start out clean
        $("#item_list").empty();
        
        // Update the page and select the correct item
        if (data.hasOwnProperty("error") && data.error !== "") {
            // Show an error if applicable
            $("#item_list").append(data.error);
        } else if (data.hasOwnProperty("records") && data.records.length === 0) {
            // Show that there are no results
            $("#item_list").append(dict["database.no_results"]);
        } else {
            // Some variables
            var page_size = <?= $page_size; ?>;
            var page_id = "<?= $page_id; ?>";
            var page_base_url = "<?= setParameters($page_base_url); ?>";
            var curr_id = "<?= $id; ?>";
            
            // Fill up the page
            for (var i = 0; i < page_size; i++) {
                var item = data.records[i];
                
                // We want a full page of items inserted. 
                // If there aren't enough items, fill the rest up with blanks
                var option = '<a class="list-group-item list-group-item-action invisible"> empty </a>';
                if (i < data.records.length) {
                    // The link to refer to
                    var href = page_base_url + "/" + item.id;
                    
                    // If an option in the sidebar is selected, it needs to be highlighted
                    var classes = "list-group-item list-group-item-action";
                    if (curr_id === item.id) {
                        classes = classes + " active";
                    }
                    
                    // The name to be shown in the sidebar
                    var value = item.name;
                    if (item.hasOwnProperty("aka") && item.aka !== "") {
                        // The AKA value is only given when searching for a name and there is a hit
                        // with an AKA value.
                        value = value + " (" + item.aka + ")";
                    }
            
<?php if (array_search($page_id, $map) !== false) { ?>
                    var onclick = "showLoadingScreen()";
                    if (page_id === "worldmap") {
                        onclick = "getLinkToMap(" + item.id + ")";
                        href = "javascript: void(0)";
                    }
<?php } else { ?>
                    var onclick = "";
<?php } ?>
                    
                    var option = '<a href="' + href + '" class="' + classes + '" onclick="' + onclick + '">' + value + '</a>';
                }
                
                $("#item_list").append(option);
            }
        }
        
        total_num_pages = 0;
        if (data.hasOwnProperty("paging") && data.paging !== "") {
            total_num_pages = parseInt(data.paging, 10);
        }
        
        // Make the pagination visible/invisible
        if (total_num_pages > 1) {
            // pagination
            $("#item_pagination").addClass("visible");
            $("#item_pagination").removeClass("invisible");
            
            // Show the amount of pages
            $("#num_pages").text(total_num_pages);
        } else {
            // No pagination
            $("#item_pagination").addClass("invisible");
            $("#item_pagination").removeClass("visible");
        }
        
    });
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
    if (window.innerWidth < 768) {
        $("html, body").animate({
            scrollTop: $("#item_content").offset().top
        }, 2000);
    }
});
</script>