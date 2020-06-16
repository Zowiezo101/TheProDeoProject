/* global session_settings, showMapInfo, updateSessionSettings, getMapFromDatabase */

function showMapList(information) {

    // The item bar, where all items are shown
    var itemBar = document.getElementById("item_bar");

    // Clean it
    itemBar.innerHTML = "";

    // If there are results, create the table with the results
    var table = document.createElement("table");
    itemBar.appendChild(table);
    
    // First item is the global item
    var tableRow = document.createElement('tr');
    table.appendChild(tableRow);

    var tableData = document.createElement('td');
    tableRow.appendChild(tableData);

    var button = document.createElement('button');
    button.innerHTML = "Global item"; // TODO

    button.id = "global_id";
    button.addEventListener("click", function() {
        updateSessionSettings("map", this.id).then(getMapFromDatabase(session_settings["table"], this.id).then(showMapInfo, console.log), console.log);
    });
    tableData.appendChild(button);
        
    // nNow add all the other items
    for (var itemIdx in information) {
        var item = information[itemIdx];

        var tableRow = document.createElement('tr');
        table.appendChild(tableRow);

        var tableData = document.createElement('td');
        tableRow.appendChild(tableData);

        var button = document.createElement('button');
        button.innerHTML = item["name"];

        switch(session_settings["table"]) {
            case "timeline":
                var item_type = "event";
                break;
                
            case "familytree":
                item_type = "people";
                break;
                
            case "worldmap":
                item_type = "location";
                break;
        }
        button.id = item[item_type + "_id"];
        button.addEventListener("click", function() {
            updateSessionSettings("map", this.id).then(getMapFromDatabase(session_settings["table"], this.id).then(showMapInfo, console.log), console.log);
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
    // TODO:
//        setButtonLeft(buttonBar);
//        setButtonApp(buttonBar);
//        setButtonAlp(buttonBar);
//        setButtonRight(buttonBar);

    /* Show a list of the available items in the item bar
       When clicked, it will show information about this item. */
    var itemBar = document.createElement("div");
    left.appendChild(itemBar);

    // Set its attributes
    itemBar.id = "item_bar";
    itemBar.className = "item_" + session_settings["theme"];

    // Show the current page
    // TODO: getMapsFromDatabase ()
    // Alle dingen zonder ouders returnen (Tijdelijk ook zonder kinderen)
    // Als er een item gekozen is, wordt pas de benodigde informatie bij elkaar gezocht om er iets mee te bouwen
    // 
    // var page = session_settings["page"] ? session_settings["page"] : 0;
    // var sort = session_settings["sort"] ? session_settings["sort"] : "app";
    getMapFromDatabase(session_settings["table"]).then(showMapList, console.log);
    return left;
}

