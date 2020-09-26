/* global session_settings, updateSessionSettings, getItemFromDatabase, showItemInfo, getAmountFromDatabase */

function showItemList(information) {
    
    $("#item_bar").children().remove();

    // If there are results, create the table with the results
    var table = $("<table/>").appendTo("#item_bar");

    for (var itemIdx in information) {
        var item = information[itemIdx];
        var item_type = session_settings["table"].substr(0, session_settings["table"].length - 1);
        
        $("<button/>")
                .appendTo(       
                    // Add this button to a TD
                    $("<td/>").addClass("col px-0").appendTo(
                        // The TD to a TR
                        $("<tr/>").addClass("row mx-0").appendTo(
                            // And the TR to the table
                            table)))    
                .attr("id", item[item_type + "_id"])    // Set the ID
                .click(function() {                     // Set the onclick function
                    updateSessionSettings("id", this.id).then(
                        getItemFromDatabase(session_settings["table"], this.id).then(
                            showItemInfo, console.log), console.log);
                })
                .html(item["name"]);                    // Set the text
    }
}

function setLeftSide(parent) {
    // Left column
    var left = $("<div/>")
                    .appendTo(parent)
                    .attr("id", "item_choice")
                    .addClass("contents_left col-md-3 px-0");

    // Div with all the buttons for the item bar
    $("<div/>")
            .appendTo(left)
            .attr("id", "button_bar")
            .addClass("row mx-0")
            .append(
                // Previous page
                $("<button/>")
                    .attr("id", "button_left")
                    .addClass("col fas fa-arrow-left")
                    .click(PrevPage))
            .append(
                // Sort on Apperance
                $("<button/>")
                    .attr("id", "button_app")
                    .addClass("col fas fa-sort-numeric-down button_" + session_settings["theme"])
                    .click(SortOnAppearance))
            .append(
                // Sort on Alphabet
                $("<button/>")
                    .attr("id", "button_alp")
                    .addClass("col fas fa-sort-alpha-down button_" + session_settings["theme"])
                    .click(SortOnAlphabet))
            .append(
                // Sort on Alphabet
                $("<button/>")
                    .attr("id", "button_right")
                    .addClass("col fas fa-arrow-right")
                    .click(NextPage));

    // Initial settings
    updateButtonLeft();
    updateButtonApp();
    updateButtonAlp();
    updateButtonRight();

    /* Show a list of the available items in the item bar
       When clicked, it will show information about this item. */
    $("<div/>")
            .appendTo(left)
            .attr("id", "item_bar")
            .addClass("item_" + session_settings["theme"]);

    // Show the current page
    var page = session_settings["page"] ? session_settings["page"] : 0;
    var sort = session_settings["sort"] ? session_settings["sort"] : "app";
    getItemFromDatabase(session_settings["table"], "", "", page, getSortSql(sort)).then(showItemList, console.log);
    return left;
}

async function PrevPage() {
    // Get the stored page number
    // If there is no page number, we are already at the first page and don't need to go further back
    if (session_settings.hasOwnProperty("page")) {
        var page = parseInt(session_settings["page"], 10);

        if (page - 1 === 0) {
            // Going a page back means going to the first page
            // Remove the page property
            page = "";
        } else {
            page = page - 1;
        }

        // Show the new information
        await updateSessionSettings("page", page).then(async function () {
                updateButtonLeft();
                updateButtonRight();
                await getItemFromDatabase(session_settings["table"], 
                                          "", 
                                          "", 
                                          page ? page : 0, 
                                          getSortSql(session_settings["sort"])).then(showItemList, console.log);
            }, console.log
        );
    }
}

async function NextPage() {
    // Get the stored page number
    if (session_settings.hasOwnProperty("page")) {
        var page = parseInt(session_settings["page"], 10);
    } else {
        // No page given, means that we are at the first page
        page = 0;
    }

    // Show the new information
    await updateSessionSettings("page", page + 1).then(async function () {
            updateButtonLeft();
            updateButtonRight();
            await getItemFromDatabase(session_settings["table"], 
                                      "", 
                                      "", 
                                      page + 1, 
                                      getSortSql(session_settings["sort"])).then(showItemList, console.log);
        }, console.log
    );
}

async function SortOnAppearance() {
    // Get the stored page number
    if (session_settings.hasOwnProperty("sort")) {
        var sort = session_settings["sort"];
    } else {
        // No sort given, means that we have default sort
        sort = "app";
    }
    // New sort setting
    sort = (sort === "app") ? "r-app" : "app";

    // Show the new information
    await updateSessionSettings("sort", sort).then(async function () {
        await updateSessionSettings("page", "").then(async function () {
            updateButtonAlp();
            updateButtonApp();
            await getItemFromDatabase(session_settings["table"], 
                                      "", 
                                      "", 
                                      0, 
                                      getSortSql(sort)).then(showItemList, console.log);
        }, console.log);
    }, console.log);
}

async function SortOnAlphabet() {
    // Get the stored page number
    if (session_settings.hasOwnProperty("sort")) {
        var sort = session_settings["sort"];
    } else {
        // No sort given, means that we have default sort
        sort = "app";
    }
    // New sort setting
    sort = (sort === "alp") ? "r-alp" : "alp";

    // Show the new information
    await updateSessionSettings("sort", sort).then(async function () {
        await updateSessionSettings("page", "").then(async function () {
            updateButtonAlp();
            updateButtonApp();
            await getItemFromDatabase(session_settings["table"], 
                                      "", 
                                      "", 
                                      0, 
                                      getSortSql(sort)).then(showItemList, console.log);
        }, console.log);
    }, console.log);
}

function updateButtonLeft() {

    var page = session_settings.hasOwnProperty("page") ? session_settings["page"] : 0;
    
    $("#button_left")
            .attr("disabled", (page === 0) ? true : false)
            .removeClass(((page === 0) ? "" : "off_") + "button_" + session_settings["theme"])
            .addClass(((page !== 0) ? "" : "off_") + "button_" + session_settings["theme"]);
}

function updateButtonApp() {
    var sort = session_settings.hasOwnProperty("sort") ? session_settings["sort"] : "app";
    
    $("#button_app")
            .removeClass((sort === "app") ? "fas fa-sort-numeric-down" : "fas fa-sort-numeric-down-alt")
            .addClass((sort !== "app") ? "fas fa-sort-numeric-down" : "fas fa-sort-numeric-down-alt");
}

function updateButtonAlp() {
    var sort = session_settings.hasOwnProperty("sort") ? session_settings["sort"] : "app";
    
    $("#button_alp")
            .removeClass((sort === "alp") ? "fas fa-sort-alpha-down" : "fas fa-sort-alpha-down-alt")
            .addClass((sort !== "alp") ? "fas fa-sort-alpha-down" : "fas fa-sort-alpha-down-alt");
}

async function updateButtonRight() {
    
    await getAmountFromDatabase(session_settings["table"], 
                                "", 
                                session_settings["page"]).then(
                                    function(nrOfItems) {
    
                                        $("#button_right")
                                                .attr("disabled", (parseInt(nrOfItems, 10) < 101) ? true : false)
                                                .removeClass(((parseInt(nrOfItems, 10) < 101) ? "off_" : "") + "button_" + session_settings["theme"])
                                                .addClass(((parseInt(nrOfItems, 10) < 101) ? "off_" : "") + "button_" + session_settings["theme"]);
                                }, console.log);
}