/* global session_settings, updateSessionSettings, getItemFromDatabase, showItemInfo, getAmountFromDatabase */

function showItemList(information) {

    // The item bar, where all items are shown
    var itemBar = document.getElementById("item_bar");

    // Clean it
    itemBar.innerHTML = "";

    // If there are results, create the table with the results
    var table = document.createElement("table");
    itemBar.appendChild(table);

    for (var itemIdx in information) {
        var item = information[itemIdx];

        var tableRow = document.createElement('tr');
        table.appendChild(tableRow);

        var tableData = document.createElement('td');
        tableRow.appendChild(tableData);

        var button = document.createElement('button');
        button.innerHTML = item["name"];

        var item_type = session_settings["table"].substr(0, session_settings["table"].length - 1);
        button.id = item[item_type + "_id"];
        button.addEventListener("click", function() {
            updateSessionSettings("id", this.id).then(getItemFromDatabase(session_settings["table"], this.id).then(showItemInfo, console.log), console.log);
        });
        tableData.appendChild(button);

    }
}

function setLeftSide(parent) {
    // Left column
    var left = document.createElement("div");
    parent.appendChild(left);

    // Set its attributes
    left.id = "item_choice";
    left.className = "contents_left";

    // Div with all the buttons for the item bar
    var buttonBar = document.createElement("div");
    left.appendChild(buttonBar);

    // Set its attributes
    buttonBar.id = "button_bar";

    // Add all the buttons to it
    setButtonLeft(buttonBar);
    setButtonApp(buttonBar);
    setButtonAlp(buttonBar);
    setButtonRight(buttonBar);

    /* Show a list of the available items in the item bar
       When clicked, it will show information about this item. */
    var itemBar = document.createElement("div");
    left.appendChild(itemBar);

    // Set its attributes
    itemBar.id = "item_bar";
    itemBar.className = "item_" + session_settings["theme"];

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

function setButtonLeft(parent) {
    // Previous page
    var buttonLeft = document.createElement("button");
    parent.appendChild(buttonLeft);

    // Set its attributes
    buttonLeft.id = "button_left";
    buttonLeft.onclick = PrevPage;
    buttonLeft.innerHTML = "←";

    updateButtonLeft();
}

function updateButtonLeft() {
    var buttonLeft = document.getElementById("button_left");

    if (session_settings.hasOwnProperty("page")) {
        var page = session_settings["page"];
    } else {
        page = 0;
    }

    buttonLeft.disabled = (page === 0) ? true : false;
    buttonLeft.className = ((page === 0) ? "off_" : "") + "button_" + session_settings["theme"];
}

function setButtonApp(parent) {
    // Sort on Apperance
    var buttonApp = document.createElement("button");
    parent.appendChild(buttonApp);

    // Set its attributes
    buttonApp.id = "button_app";
    buttonApp.onclick = SortOnAppearance;

    updateButtonApp();
}

function updateButtonApp() {
    var buttonApp = document.getElementById("button_app");

    if (session_settings.hasOwnProperty("sort")) {
        var sort = session_settings["sort"];
    } else {
        sort = "app";
    }

    buttonApp.className = (sort === "app") ? "sort_9_1" : "sort_1_9";   
}

function setButtonAlp(parent) {
    // Sort on Alphabet
    var buttonAlp = document.createElement("button");
    parent.appendChild(buttonAlp);

    // Set its attributes
    buttonAlp.id = "button_alp";
    buttonAlp.onclick = SortOnAlphabet;

    updateButtonAlp();
}

function updateButtonAlp() {
    var buttonAlp = document.getElementById("button_alp");

    if (session_settings.hasOwnProperty("sort")) {
        var sort = session_settings["sort"];
    } else {
        sort = "app";
    }

    buttonAlp.className = (sort === "alp") ? "sort_z_a" : "sort_a_z";   
}

function setButtonRight(parent) {
    // Next page
    var buttonRight = document.createElement("button");
    parent.appendChild(buttonRight);

    // Set its attributes
    buttonRight.id = "button_right";
    buttonRight.onclick = NextPage;
    buttonRight.innerHTML = "→";

    updateButtonRight();
}

async function updateButtonRight() {
    
    await getAmountFromDatabase(session_settings["table"], 
                                "", 
                                session_settings["page"]).then(function(nrOfItems) {
                                    var buttonRight = document.getElementById("button_right");
                                    
                                    buttonRight.disabled = (parseInt(nrOfItems, 10) < 101) ? true : false;
                                    buttonRight.className = ((parseInt(nrOfItems, 10) < 101) ? "off_" : "") + "button_" + session_settings["theme"];
                                }, console.log);
}